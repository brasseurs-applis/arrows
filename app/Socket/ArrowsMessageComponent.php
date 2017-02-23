<?php

namespace BrasseursApplis\Arrows\App\Socket;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\App\Security\UnauthorizedException;
use BrasseursApplis\Arrows\App\Security\Voter\SessionVoter;
use BrasseursApplis\Arrows\App\Socket\Connection\ArrowsConnectionInformation;
use BrasseursApplis\Arrows\App\Socket\Connection\SessionConnections;
use BrasseursApplis\Arrows\App\Socket\Message\Inbound\SessionResult;
use BrasseursApplis\Arrows\App\Socket\Message\Inbound\StartSession;
use BrasseursApplis\Arrows\App\Socket\Message\Outbound\Error;
use BrasseursApplis\Arrows\App\Socket\Message\Outbound\SessionEnded;
use BrasseursApplis\Arrows\App\Socket\Message\Outbound\SessionReady;
use BrasseursApplis\Arrows\App\Socket\Message\Outbound\SessionSequence;
use BrasseursApplis\Arrows\Exception\ScenarioException;
use BrasseursApplis\Arrows\Id\SessionId;
use BrasseursApplis\Arrows\Repository\SessionRepository;
use BrasseursApplis\Arrows\Session;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use RemiSan\Silex\JWT\Security\JwtAuthenticator;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ArrowsMessageComponent implements MessageComponentInterface
{
    /** @var SessionConnections[] */
    protected $sessionClients;

    /** @var SessionRepository */
    protected $sessionRepository;

    /** @var JwtAuthenticator */
    protected $jwtAuthenticator;

    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    /**
     * ArrowsMessageComponent constructor.
     *
     * @param SessionRepository             $sessionRepository
     * @param JwtAuthenticator              $jwtAuthenticator
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        SessionRepository $sessionRepository,
        JwtAuthenticator $jwtAuthenticator,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->sessionRepository = $sessionRepository;
        $this->jwtAuthenticator = $jwtAuthenticator;
        $this->authorizationChecker = $authorizationChecker;

        $this->sessionClients = [];
    }

    /**
     * @param ConnectionInterface $conn
     *
     * @throws AssertionFailedException
     * @throws UnauthorizedException
     * @throws \InvalidArgumentException
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->protect(
            $conn,
            function (ArrowsConnectionInformation $connectionInformation) {
                // TODO work on role to remove role name from url

                $sessionConnections = $this->getSessionConnections($connectionInformation->getSessionId());
                $sessionConnections->register($connectionInformation);

                if ($sessionConnections->isComplete()) {
                    $sessionConnections->broadcast(new SessionReady());
                }
            }
        );
    }

    /**
     * @param ConnectionInterface $conn
     * @param string              $msg
     *
     * @throws \InvalidArgumentException
     * @throws AssertionFailedException
     * @throws UnauthorizedException
     * @throws ScenarioException
     */
    public function onMessage(ConnectionInterface $conn, $msg)
    {
        $this->protect(
            $conn,
            function (ArrowsConnectionInformation $connectionInformation, Session $session) use ($msg) {
                $sessionConnections = $this->getSessionConnections($connectionInformation->getSessionId());

                $message = self::parseMessage($msg);
                $response = $this->handleMessage($connectionInformation, $session, $message);

                $this->sessionRepository->persist($session);

                $sessionConnections->broadcast($response);
            }
        );
    }

    /**
     * @param ConnectionInterface $conn
     *
     * @throws AssertionFailedException
     * @throws UnauthorizedException
     * @throws \InvalidArgumentException
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->protect(
            $conn,
            function (ArrowsConnectionInformation $connectionInformation, Session $session) {
                $sessionConnections = $this->getSessionConnections($connectionInformation->getSessionId());
                $sessionConnections->unregister($connectionInformation);

                $sessionConnections->broadcast(new SessionEnded());
            }
        );
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception          $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Closure            $protected
     *
     * @throws \InvalidArgumentException
     * @throws AssertionFailedException
     * @throws UnauthorizedException
     */
    private function protect(ConnectionInterface $conn, \Closure $protected)
    {
        try {
            $connectionInformation = new ArrowsConnectionInformation($conn);
            $connectionInformation->authenticate($this->jwtAuthenticator);

            $session = $this->sessionRepository->get(new SessionId($connectionInformation->getSessionId()));

            if ($session === null) {
                throw new \InvalidArgumentException('Session not found');
            }

            if (! $this->authorizationChecker->isGranted(SessionVoter::ACCESS, $session)) {
                throw new UnauthorizedException('User cannot access the session');
            }

            $protected($connectionInformation, $session);
        } catch (\Exception $e) {
            $connectionInformation->send(new Error($e->getMessage()));
        }
    }

    /**
     * @param $sessionId
     *
     * @return SessionConnections
     */
    private function getSessionConnections($sessionId)
    {
        if (! isset($this->sessionClients[$sessionId])) {
            $this->sessionClients[$sessionId] = new SessionConnections($sessionId);
        }

        return $this->sessionClients[$sessionId];
    }

    /**
     * @param ArrowsConnectionInformation $connectionInformation
     * @param Session                     $session
     * @param Message                     $message
     *
     * @return \JsonSerializable
     * @throws \InvalidArgumentException
     * @throws ScenarioException
     * @throws AssertionFailedException
     * @throws UnauthorizedException
     */
    private function handleMessage(
        ArrowsConnectionInformation $connectionInformation,
        Session $session,
        Message $message
    ) {
        // Start message: run session & return first sequence
        if ($message instanceof StartSession) {
            if (! $this->authorizationChecker->isGranted(SessionVoter::OBSERVE, $session)) {
                throw new UnauthorizedException('You must be the observer to launch the test');
            }


            return new SessionSequence($session->resume());
        }

        // Response message: send response & return next sequence
        if ($message instanceof SessionResult) {
            if (! $this->authorizationChecker->isGranted(SessionVoter::RESPOND, $session)) {
                throw new UnauthorizedException('You must be in position 1 to send a result');
            }

            $sequence = $session->result($message->getOrientation(), $message->getDuration());

            $sessionConnections = $this->getSessionConnections($connectionInformation->getSessionId());
            $sessionConnections->sendToObserver($message);

            // if last response : return end message
            if ($sequence === null) {
                return new SessionEnded();
            }

            return new SessionSequence($sequence);
        }

        throw new \InvalidArgumentException('Message not handled');
    }

    /**
     * @param string $msg
     *
     * @return Message
     *
     * @throws \InvalidArgumentException
     */
    private static function parseMessage($msg)
    {
        $unserialized = json_decode($msg);

        switch ($unserialized->type) {
            case StartSession::TYPE:
                return new StartSession();
            case SessionResult::TYPE:
                return new SessionResult(
                    $unserialized->orientation,
                    $unserialized->startingTime,
                    $unserialized->endingTime
                );
            default:
                throw new \InvalidArgumentException('Message not recognized');
        }
    }
}
