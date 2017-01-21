<?php

namespace BrasseursApplis\Arrows\App\Message;

use BrasseursApplis\Arrows\App\Security\SessionVoter;
use BrasseursApplis\Arrows\App\ServiceProvider\JwtAuthenticator;
use BrasseursApplis\Arrows\Id\SessionId;
use BrasseursApplis\Arrows\Repository\SessionRepository;
use BrasseursApplis\Arrows\Session;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ArrowsMessageComponent implements MessageComponentInterface
{
    /** @var SessionConnections */
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
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $connectionInformation = new ArrowsConnectionInformation($conn);

        try {
            $connectionInformation->authenticate($this->jwtAuthenticator);

            $session = $this->sessionRepository->get(new SessionId($connectionInformation->getSessionId()));

            // check user can join session
            $this->authorizationChecker->isGranted(SessionVoter::ACCESS, $session);

            if ($session === null) {
                throw new \InvalidArgumentException('Session not found');
            }

            $sessionConnections = $this->getSessionConnections($connectionInformation->getSessionId());
            $sessionConnections->register($connectionInformation);

            if ($sessionConnections->isComplete()) {
                $sessionConnections->broadcast(new SessionReady());
            }
        } catch (\Exception $e) {
            $connectionInformation->send(new Error($e->getMessage()));
        }
    }

    /**
     * @param ConnectionInterface $from
     * @param string              $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $connectionInformation = new ArrowsConnectionInformation($from);

        try {
            $connectionInformation->authenticate($this->jwtAuthenticator);

            $session = $this->sessionRepository->get(new SessionId($connectionInformation->getSessionId()));

            if ($session === null) {
                throw new \InvalidArgumentException('Session not found');
            }

            // TODO check user is in session with voters

            $sessionConnections = $this->getSessionConnections($connectionInformation->getSessionId());

            $message = self::parseMessage($msg);
            $response = $this->handleMessage($connectionInformation, $session, $message);

            $this->sessionRepository->persist($session);

            $sessionConnections->broadcast($response);
        } catch (\Exception $e) {
            $connectionInformation->send(new Error($e->getMessage()));
        }
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        $connectionInformation = new ArrowsConnectionInformation($conn);

        try {
            $connectionInformation->authenticate($this->jwtAuthenticator);

            $sessionConnections = $this->getSessionConnections($connectionInformation->getSessionId());
            $sessionConnections->unregister($connectionInformation);

            $session = $this->sessionRepository->get(new SessionId($connectionInformation->getSessionId()));
            if ($session === null) {
                return;
            }

            $session->cancel();
            $this->sessionRepository->persist($session);

            $sessionConnections->broadcast(new SessionEnded());
        } catch (\Exception $e) {
            $connectionInformation->send(new Error($e->getMessage()));
        }
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
     */
    private function handleMessage(
        ArrowsConnectionInformation $connectionInformation,
        Session $session,
        Message $message
    ) {
        // Start message (check source) : run session & return first sequence
        if ($message instanceof StartSession) {
            if ($connectionInformation->getRole() !== SessionConnections::ROLE_OBSERVER) {
                throw new \InvalidArgumentException('You must be the observer to launch the test');
            }
            return new SessionSequence($session->start());
        }

        // Response message (check source) : send response & return next sequence
        if ($message instanceof SessionResult) {
            if ($connectionInformation->getRole() !== SessionConnections::ROLE_POSITION_ONE) {
                throw new \InvalidArgumentException('You must be in position 1 to send a result');
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
