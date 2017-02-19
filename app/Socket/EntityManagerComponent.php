<?php

namespace BrasseursApplis\Arrows\App\Socket;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use React\EventLoop\LoopInterface;

class EntityManagerComponent implements MessageComponentInterface
{
    /** @var MessageComponentInterface */
    private $messageComponent;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var LoopInterface */
    private $loop;

    /**
     * EntityManagerComponent constructor.
     *
     * @param MessageComponentInterface $messageComponent
     * @param EntityManagerInterface    $entityManager
     * @param LoopInterface             $loop
     */
    public function __construct(
        MessageComponentInterface $messageComponent,
        EntityManagerInterface $entityManager,
        LoopInterface $loop
    ) {
        $this->messageComponent = $messageComponent;
        $this->entityManager = $entityManager;
        $this->loop = $loop;
    }

    /**
     * When a new connection is opened it will be passed to this method
     *
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     *
     * @throws \Exception
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->protectCall(function () use ($conn) {
            $this->messageComponent->onOpen($conn);
        });
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     *
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     *
     * @throws \Exception
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->protectCall(function () use ($conn) {
            $this->messageComponent->onClose($conn);
        });
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     *
     * @param  ConnectionInterface $conn
     * @param  \Exception          $e
     *
     * @throws \Exception
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->protectCall(function () use ($conn, $e) {
            $this->messageComponent->onError($conn, $e);
        });
    }

    /**
     * Triggered when a client sends data through the socket
     *
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string                       $msg  The message received
     *
     * @throws \Exception
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $this->protectCall(function () use ($from, $msg) {
            $this->messageComponent->onMessage($from, $msg);
        });
    }

    /**
     * @param \Closure $protected
     *
     * @throws ORMException
     * @throws DBALException
     */
    public function protectCall(\Closure $protected)
    {
        if (! $this->entityManager->isOpen()) {
            $this->loop->stop();

            throw new ORMException('Entity Manager is closed.');
        }

        try {
            $protected();
        } catch (DBALException $e) {
            $this->loop->stop();

            throw $e;
        }
    }
}
