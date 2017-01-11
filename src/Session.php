<?php

namespace BrasseursDApplis\Arrows;

use Assert\Assertion;
use BrasseursDApplis\Arrows\VO\Duration;
use BrasseursDApplis\Arrows\Id\SessionId;
use BrasseursDApplis\Arrows\VO\Orientation;
use BrasseursDApplis\Arrows\VO\Result;
use BrasseursDApplis\Arrows\VO\Sequence;

class Session
{
    /** @var SessionId */
    private $id;

    /** @var Scenario */
    private $scenario;

    /** @var Sequence */
    private $sequence;

    /** @var Result[] */
    private $results;

    /**
     * Session constructor.
     *
     * @param SessionId $id
     * @param Scenario  $scenario
     */
    public function __construct(SessionId $id, Scenario $scenario)
    {
        $this->id = $id;
        $this->setScenario($scenario);
        $this->results = [];
    }

    /**
     * @return SessionId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Scenario
     */
    public function getScenario()
    {
        return $this->scenario;
    }

    /**
     * @return Sequence
     */
    public function start()
    {
        $this->ensureThereIsNoPendingSequence('You cannot start a session already started');

        $this->sequence = $this->scenario->run();
        $this->results = [];

        return $this->sequence;
    }

    /**
     * @return Result[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param Orientation $orientation
     * @param Duration    $duration
     *
     * @return Sequence
     */
    public function result(Orientation $orientation, Duration $duration)
    {
        $this->ensureThereIsACurrentSequence();

        $result = new Result($this->sequence, $orientation, $duration);

        $this->results[] = $result;
        $this->sequence = $this->next();

        return $this->sequence;
    }

    /**
     * @return Sequence
     */
    private function next()
    {
        if (!$this->scenario->hasNext()) {
            return null;
        }

        return $this->scenario->next();
    }

    /**
     * @param $scenario
     */
    private function setScenario(Scenario $scenario)
    {
        Assertion::true($scenario->isComplete(), 'You can only add a complete scenario.');

        $this->scenario = $scenario;
    }

    protected function ensureThereIsNoPendingSequence($message)
    {
        Assertion::null($this->sequence, $message);
    }

    protected function ensureThereIsACurrentSequence()
    {
        Assertion::notNull($this->sequence, 'You cannot add a second result for the current sequence.');
    }
}
