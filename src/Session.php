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
        Assertion::null($this->sequence, 'You cannot start an already started session.');

        $this->sequence = $this->scenario->run();
        $this->results = [];

        return $this->sequence;
    }

    /**
     * @param Orientation $orientation
     * @param Duration    $duration
     *
     * @return Result
     */
    public function result(Orientation $orientation, Duration $duration)
    {
        Assertion::notNull($this->sequence, 'You cannot add a second result for the current sequence.');

        $result = new Result($this->sequence, $orientation, $duration);

        $this->results[] = $result;
        $this->sequence = null;

        return $result;
    }

    /**
     * @param $scenario
     */
    private function setScenario(Scenario $scenario)
    {
        Assertion::true($scenario->isComplete(), 'You can only add a complete scenario.');

        $this->scenario = $scenario;
    }
}
