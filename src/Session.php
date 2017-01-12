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

        $this->results = [];
        return $this->scenario->run();
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

        $result = new Result($this->scenario->current(), $orientation, $duration);

        $this->results[] = $result;

        return $this->next();
    }

    /**
     * @return Result[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return Sequence
     */
    private function next()
    {
        if (!$this->scenario->hasNext()) {
            $this->scenario->stop();
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
        Assertion::false($this->scenario->isRunning(), $message);
    }

    protected function ensureThereIsACurrentSequence()
    {
        Assertion::true($this->scenario->isRunning(), 'You cannot add a result matching no sequence.');
    }
}
