<?php

namespace BrasseursDApplis\Arrows;

use BrasseursDApplis\Arrows\Exception\ScenarioAssertion;
use BrasseursDApplis\Arrows\Exception\ScenarioException;
use BrasseursDApplis\Arrows\Id\ResearcherId;
use BrasseursDApplis\Arrows\Id\ScenarioTemplateId;
use BrasseursDApplis\Arrows\VO\Scenario;
use BrasseursDApplis\Arrows\VO\Sequence;
use BrasseursDApplis\Arrows\VO\SequenceCollection;

class ScenarioTemplate
{
    /** @var ScenarioTemplateId */
    private $id;

    /** @var ResearcherId */
    private $author;

    /** @var string */
    private $name;

    /** @var int */
    private $nbSequences;

    /** @var SequenceCollection */
    private $sequences;

    /**
     * ScenarioTemplate constructor.
     *
     * @param ScenarioTemplateId $id
     * @param ResearcherId       $author
     * @param string             $name
     * @param int                $nbSequences
     */
    public function __construct(ScenarioTemplateId $id, ResearcherId $author, $name, $nbSequences)
    {
        $this->id = $id;
        $this->author = $author;
        $this->name = $name;
        $this->nbSequences = $nbSequences;
        $this->sequences = new SequenceCollection();
    }

    /**
     * @return ScenarioTemplateId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ResearcherId
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getNbSequences()
    {
        return $this->nbSequences;
    }

    /**
     * @param Sequence $sequence
     */
    public function addSequence(Sequence $sequence)
    {
        $this->ensureScenarioIsNotCompleteYet();

        $this->sequences->add($sequence);
    }

    /**
     * @param int      $position
     * @param Sequence $sequence
     */
    public function replaceSequence($position, Sequence $sequence)
    {
        $this->ensureSequenceExistsAtPosition($position);

        $this->sequences->set($position, $sequence);
    }

    /**
     * @param int $position
     *
     * @return Sequence
     */
    public function getSequence($position)
    {
        $this->ensureSequenceExistsAtPosition($position);

        return $this->sequences->get($position);
    }

    /**
     * @return Scenario
     */
    public function getScenario()
    {
        $this->ensureScenarioIsComplete();

        return new Scenario($this->sequences);
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        return $this->sequences->count() === $this->nbSequences;
    }

    /**
     * @throws ScenarioException
     */
    private function ensureScenarioIsNotCompleteYet()
    {
        ScenarioAssertion::lessThan(
            $this->sequences->count(),
            $this->nbSequences,
            'ScenarioTemplate is already complete.'
        );
    }

    /**
     * @throws ScenarioException
     */
    private function ensureScenarioIsComplete()
    {
        ScenarioAssertion::true($this->isComplete(), 'Scenario template is not complete.');
    }

    /**
     * @param $position
     *
     * @throws ScenarioException
     */
    private function ensureSequenceExistsAtPosition($position)
    {
        ScenarioAssertion::notNull($this->sequences->get($position), 'No sequence at this position.');
    }
}
