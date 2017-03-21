<?php

namespace BrasseursApplis\Arrows\VO;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\Exception\ScenarioAssertion;
use BrasseursApplis\Arrows\Exception\ScenarioException;

class Scenario implements \JsonSerializable
{
    /** @var SequenceCollection */
    private $sequences;

    /** @var int */
    private $currentPosition;

    /**
     * Scenario constructor.
     *
     * @param SequenceCollection $sequences
     */
    public function __construct(SequenceCollection $sequences)
    {
        $this->sequences = $sequences;
        $this->currentPosition = null;
    }

    /**
     * @return Sequence
     *
     * @throws ScenarioException
     * @throws AssertionFailedException
     */
    public function run()
    {
        $this->ensureScenarioIsNotAlreadyRunning();

        $this->currentPosition = 0;

        return $this->current();
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
     *
     * @throws ScenarioException
     * @throws AssertionFailedException
     */
    public function current()
    {
        $this->ensureScenarioIsRunning();

        return $this->sequences->get($this->currentPosition);
    }

    /**
     * @return Sequence
     *
     * @throws ScenarioException
     * @throws AssertionFailedException
     */
    public function next()
    {
        $this->ensureScenarioIsRunning();
        $this->ensureNextPositionIsInBounds();

        $this->currentPosition++;

        return $this->current();
    }

    /**
     * @return bool
     */
    public function isRunning()
    {
        return $this->currentPosition !== null;
    }

    /**
     * @return bool
     */
    public function hasNext()
    {
        return $this->currentPosition < $this->sequences->count() - 1;
    }

    /**
     * @throws ScenarioException
     * @throws AssertionFailedException
     */
    private function ensureNextPositionIsInBounds()
    {
        ScenarioAssertion::true($this->hasNext(), 'There is no next position.');
    }

    /**
     * @throws ScenarioException
     * @throws AssertionFailedException
     */
    private function ensureScenarioIsNotAlreadyRunning()
    {
        ScenarioAssertion::null($this->currentPosition, 'ScenarioTemplate is already running.');
    }

    /**
     * @throws ScenarioException
     * @throws AssertionFailedException
     */
    private function ensureScenarioIsRunning()
    {
        ScenarioAssertion::notNull($this->currentPosition, 'ScenarioTemplate is not running.');
    }

    /**
     * @return int
     */
    public function countSequences()
    {
        return $this->sequences->count();
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *        which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'sequences' => $this->sequences,
            'currentPosition' => $this->currentPosition
        ];
    }

    /**
     * @param array $array
     *
     * @return Scenario
     *
     * @throws AssertionFailedException
     */
    public static function fromJsonArray(array $array)
    {
        $scenario = new self(SequenceCollection::fromJsonArray($array['sequences']));
        $scenario->currentPosition = $array['currentPosition'];

        return $scenario;
    }
}
