<?php

namespace BrasseursApplis\Arrows;

use Assert\Assertion;
use BrasseursApplis\Arrows\Id\ResearcherId;
use BrasseursApplis\Arrows\Id\SessionId;
use BrasseursApplis\Arrows\VO\Duration;
use BrasseursApplis\Arrows\VO\Orientation;
use BrasseursApplis\Arrows\VO\Result;
use BrasseursApplis\Arrows\VO\ResultCollection;
use BrasseursApplis\Arrows\VO\Scenario;
use BrasseursApplis\Arrows\VO\Sequence;
use BrasseursApplis\Arrows\VO\SubjectsCouple;
use Doctrine\Common\Collections\Collection;

class Session
{
    /** @var SessionId */
    private $id;

    /** @var Scenario */
    private $scenario;

    /** @var SubjectsCouple */
    private $subjects;

    /** @var ResearcherId */
    private $observer;

    /** @var Result[] | Collection */
    private $results;

    /**
     * Session constructor.
     *
     * @param SessionId      $id
     * @param Scenario       $scenario
     * @param SubjectsCouple $subjects
     * @param ResearcherId   $observer
     */
    public function __construct(
        SessionId $id,
        Scenario $scenario,
        SubjectsCouple $subjects,
        ResearcherId $observer
    ) {
        $this->id = $id;
        $this->scenario = $scenario;
        $this->subjects = $subjects;
        $this->observer = $observer;
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
     * @return SubjectsCouple
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @return ResearcherId
     */
    public function getObserver()
    {
        return $this->observer;
    }

    /**
     * @return Sequence
     */
    public function start()
    {
        $this->ensureThereIsNoPendingSequence('You cannot start a session already started');

        $this->results = new ResultCollection();

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

        $this->results->add($result);

        return $this->next();
    }

    /**
     * @return void
     */
    public function cancel()
    {
        $this->scenario->stop();
        $this->results = new ResultCollection();
    }

    /**
     * @return Result[] | Collection
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

    protected function ensureThereIsNoPendingSequence($message)
    {
        Assertion::false($this->scenario->isRunning(), $message);
    }

    protected function ensureThereIsACurrentSequence()
    {
        Assertion::true($this->scenario->isRunning(), 'You cannot add a result matching no sequence.');
    }
}
