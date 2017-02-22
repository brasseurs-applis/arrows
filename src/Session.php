<?php

namespace BrasseursApplis\Arrows;

use Assert\Assertion;
use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\Id\ResearcherId;
use BrasseursApplis\Arrows\Id\ScenarioTemplateId;
use BrasseursApplis\Arrows\Id\SessionId;
use BrasseursApplis\Arrows\VO\Duration;
use BrasseursApplis\Arrows\VO\Orientation;
use BrasseursApplis\Arrows\VO\Scenario;
use BrasseursApplis\Arrows\VO\Sequence;
use BrasseursApplis\Arrows\VO\SubjectsCouple;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Session
{
    /** @var SessionId */
    private $id;

    /** @var ScenarioTemplateId */
    private $scenarioTemplateId;

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
     * @param SessionId          $id
     * @param ScenarioTemplateId $scenarioTemplateId
     * @param Scenario           $scenario
     * @param SubjectsCouple     $subjects
     * @param ResearcherId       $observer
     */
    public function __construct(
        SessionId $id,
        ScenarioTemplateId $scenarioTemplateId,
        Scenario $scenario,
        SubjectsCouple $subjects,
        ResearcherId $observer
    ) {
        $this->id = $id;
        $this->scenarioTemplateId = $scenarioTemplateId;
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
     * @return ScenarioTemplateId
     */
    public function getScenarioTemplateId()
    {
        return $this->scenarioTemplateId;
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
     * @param ResearcherId $observer
     */
    public function changeObserver(ResearcherId $observer)
    {
        $this->ensureSessionIsModifiable();

        $this->observer = $observer;
    }

    /**
     * @param SubjectsCouple $subjects
     */
    public function changeSubjects(SubjectsCouple $subjects)
    {
        $this->ensureSessionIsModifiable();

        $this->subjects = $subjects;
    }

    /**
     * @param ScenarioTemplateId $scenarioTemplateId
     * @param Scenario           $scenario
     */
    public function changeScenario(ScenarioTemplateId $scenarioTemplateId, Scenario $scenario)
    {
        $this->ensureSessionIsModifiable();

        $this->scenarioTemplateId = $scenarioTemplateId;
        $this->scenario = $scenario;
    }

    /**
     * @return Sequence
     *
     * @throws Exception\ScenarioException
     * @throws AssertionFailedException
     */
    public function start()
    {
        $this->ensureThereIsNoPendingSequence('You cannot start a session already started');

        $this->results = new ArrayCollection();

        return $this->scenario->run();
    }

    /**
     * @return Sequence
     *
     * @throws Exception\ScenarioException
     * @throws \Assert\AssertionFailedException
     */
    public function resume()
    {
        if (!$this->scenario->isRunning()) {
            return $this->start();
        }

        return $this->scenario->current();
    }

    /**
     * @param Orientation $orientation
     * @param Duration    $duration
     *
     * @return Sequence
     *
     * @throws Exception\ScenarioException
     * @throws AssertionFailedException
     */
    public function result(Orientation $orientation, Duration $duration)
    {
        $this->ensureThereIsACurrentSequence();

        $result = new Result($this, $this->scenario->current(), $orientation, $duration);

        $this->results->add($result);

        return $this->next();
    }

    /**
     * @return Result[]
     */
    public function getResults()
    {
        return $this->results->getValues();
    }

    /**
     * @return Sequence
     *
     * @throws Exception\ScenarioException
     * @throws AssertionFailedException
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
        Assertion::true($this->results->isEmpty(), $message);
    }

    protected function ensureThereIsACurrentSequence()
    {
        Assertion::true($this->scenario->isRunning(), 'You cannot add a result matching no sequence.');
    }

    private function ensureSessionIsModifiable()
    {
        Assertion::true($this->results->isEmpty(), 'You cannot modify a started session');
    }
}
