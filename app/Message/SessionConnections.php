<?php

namespace BrasseursDApplis\Arrows\App\Message;

use Assert\Assertion;
use Ratchet\ConnectionInterface;

class SessionConnections
{
    const ROLE_OBSERVER = 'observer';
    const ROLE_POSITION_ONE = 'position1';
    const ROLE_POSITION_TWO = 'position2';

    /** @var string */
    private $id;

    /** @var ConnectionInterface */
    private $observerConnection;

    /** @var ConnectionInterface */
    private $subjectOneConnection;

    /** @var ConnectionInterface */
    private $subjectTwoConnection;

    /**
     * SessionConnections constructor.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param ArrowsConnectionInformation $connectionInformation
     */
    public function register(ArrowsConnectionInformation $connectionInformation)
    {
        $this->assertValidRole($connectionInformation->getRole());

        switch ($connectionInformation->getRole()) {
            case self::ROLE_OBSERVER:
                $this->observerConnection = $connectionInformation->getConnection();
                return;
            case self::ROLE_POSITION_ONE:
                $this->subjectOneConnection = $connectionInformation->getConnection();
                return;
            case self::ROLE_POSITION_TWO:
                $this->subjectTwoConnection = $connectionInformation->getConnection();
                return;
        }
    }

    /**
     * @param ArrowsConnectionInformation $connectionInformation
     */
    public function unregister(ArrowsConnectionInformation $connectionInformation)
    {
        $this->assertValidRole($connectionInformation->getRole());

        switch ($connectionInformation->getRole()) {
            case self::ROLE_OBSERVER:
                $this->observerConnection = null;
                return;
            case self::ROLE_POSITION_ONE:
                $this->subjectOneConnection = null;
                return;
            case self::ROLE_POSITION_TWO:
                $this->subjectTwoConnection = null;
                return;
        }
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        return $this->observerConnection !== null
            && $this->subjectOneConnection !== null
            && $this->subjectTwoConnection !== null;
    }

    /**
     * @param \JsonSerializable $message
     *
     * @internal param $serializedObject
     */
    public function sendToObserver(\JsonSerializable $message)
    {
        $serializedObject = json_encode($message);

        if ($this->observerConnection !== null) {
            $this->observerConnection->send($serializedObject);
        }
    }

    /**
     * @param \JsonSerializable $message
     *
     * @internal param $serializedObject
     */
    public function sendToSubjectOne(\JsonSerializable $message)
    {
        $serializedObject = json_encode($message);

        if ($this->subjectOneConnection !== null) {
            $this->subjectOneConnection->send($serializedObject);
        }
    }

    /**
     * @param \JsonSerializable $message
     *
     * @internal param $serializedObject
     */
    public function sendToSubjectTwo(\JsonSerializable $message)
    {
        $serializedObject = json_encode($message);

        if ($this->subjectTwoConnection !== null) {
            $this->subjectTwoConnection->send($serializedObject);
        }
    }

    /**
     * @param \JsonSerializable $message
     */
    public function broadcast(\JsonSerializable $message)
    {
        $this->sendToObserver($message);
        $this->sendToSubjectOne($message);
        $this->sendToSubjectTwo($message);
    }

    /**
     * @param $role
     */
    protected function assertValidRole($role)
    {
        Assertion::choice($role, [self::ROLE_OBSERVER, self::ROLE_POSITION_ONE, self::ROLE_POSITION_TWO],
                          'Role unknown');
    }
}
