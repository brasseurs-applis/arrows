<?php

namespace BrasseursApplis\Arrows\App\Socket\Connection;

use Guzzle\Http\Message\RequestInterface;
use Ratchet\ConnectionInterface;
use RemiSan\Silex\JWT\Security\JwtAuthenticator;

class ArrowsConnectionInformation
{
    /** @var ConnectionInterface */
    private $connection;

    /** @var RequestInterface */
    private $request;

    /**
     * ArrowsConnectionInformation constructor.
     *
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->request = $connection->WebSocket->request;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return $this->connection->resourceId;
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->request->getQuery()->get('sessionId');
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->request->getQuery()->get('role');
    }

    /**
     * @param \JsonSerializable $message
     */
    public function send(\JsonSerializable $message)
    {
        $serializedMessage = json_encode($message);

        $this->connection->send($serializedMessage);
    }

    /**
     * @param JwtAuthenticator $authenticator
     */
    public function authenticate(JwtAuthenticator $authenticator)
    {
        $authenticator->authenticate($this->request);
    }
}
