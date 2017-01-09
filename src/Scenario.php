<?php

namespace BrasseursDApplis\Arrows;

use BrasseursDApplis\Arrows\Exception\ScenarioAssertion;
use BrasseursDApplis\Arrows\Exception\ScenarioException;
use BrasseursDApplis\Arrows\Id\ScenarioId;
use BrasseursDApplis\Arrows\VO\Sequence;

class Scenario
{
    /** @var ScenarioId */
    private $id;

    /** @var string */
    private $name;

    /** @var int */
    private $nbSequences;

    /** @var Sequence[] */
    private $sequences;

    /** @var int */
    private $currentPosition;

    /**
     * Scenario constructor.
     *
     * @param ScenarioId $id
     * @param string     $name
     * @param int        $nbSequences
     */
    public function __construct(ScenarioId $id, $name, $nbSequences)
    {
        $this->id = $id;
        $this->name = $name;
        $this->nbSequences = $nbSequences;
        $this->sequences = [];
        $this->currentPosition = null;
    }

    /**
     * @return ScenarioId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Sequence $sequence
     */
    public function addSequence(Sequence $sequence)
    {
        $this->ensureScenarioIsNotCompleteYet();

        $this->sequences[] = $sequence;
    }

    /**
     * @param int      $position
     * @param Sequence $sequence
     */
    public function replaceSequence($position, Sequence $sequence)
    {
        $this->ensureSequenceExistsAtPosition($position);

        $this->sequences[$position] = $sequence;
    }

    /**
     * @return Sequence
     */
    public function run()
    {
        $this->ensureScenarioIsComplete();
        $this->ensureScenarioIsNotAlreadyRunning();

        $this->currentPosition = 0;

        return $this->getCurrent();
    }

    /**
     * Stop the run
     */
    public function stop()
    {
        $this->currentPosition = null;
    }

    /**
     * @return Sequence
     */
    public function getCurrent()
    {
        $this->ensureScenarioIsRunning();

        return $this->sequences[$this->currentPosition];
    }

    /**
     * @return Sequence
     */
    public function getNext()
    {
        $this->ensureScenarioIsRunning();
        $this->ensureNextPositionIsInBounds();

        $this->currentPosition++;

        return $this->getCurrent();
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        return count($this->sequences) === $this->nbSequences;
    }

    /**
     * @throws ScenarioException
     */
    private function ensureScenarioIsNotCompleteYet()
    {
        ScenarioAssertion::lessThan(count($this->sequences), $this->nbSequences, 'Scenario is already complete.');
    }

    /**
     * @param $position
     *
     * @throws ScenarioException
     */
    private function ensureSequenceExistsAtPosition($position)
    {
        ScenarioAssertion::keyIsset($this->sequences, $position, 'No sequence at this position.');
    }

    /**
     * @throws ScenarioException
     */
    private function ensureScenarioIsComplete()
    {
        ScenarioAssertion::true($this->isComplete(), 'Scenario is not complete.');
    }

    /**
     * @throws ScenarioException
     */
    private function ensureNextPositionIsInBounds()
    {
        ScenarioAssertion::lessThan($this->currentPosition+1, $this->nbSequences, 'There is no next position.');
    }

    /**
     * @throws ScenarioException
     */
    private function ensureScenarioIsNotAlreadyRunning()
    {
        ScenarioAssertion::null($this->currentPosition, 'Scenario is already running.');
    }

    /**
     * @throws ScenarioException
     */
    private function ensureScenarioIsRunning()
    {
        ScenarioAssertion::notNull($this->currentPosition, 'Scenario is not running.');
    }
}
