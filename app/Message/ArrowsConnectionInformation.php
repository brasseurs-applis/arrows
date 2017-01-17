<?php

namespace BrasseursApplis\Arrows\App\Message;

use Guzzle\Http\Message\EntityEnclosingRequest;
use Ratchet\ConnectionInterface;

class ArrowsConnectionInformation
{
    /** @var ConnectionInterface */
    private $connection;

    /** @var EntityEnclosingRequest */
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
}
