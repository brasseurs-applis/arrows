<?php

namespace BrasseursApplis\Arrows;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\Exception\ScenarioAssertion;
use BrasseursApplis\Arrows\Exception\ScenarioException;
use BrasseursApplis\Arrows\Id\ResearcherId;
use BrasseursApplis\Arrows\Id\ScenarioTemplateId;
use BrasseursApplis\Arrows\VO\Scenario;
use BrasseursApplis\Arrows\VO\Sequence;
use BrasseursApplis\Arrows\VO\SequenceCollection;

class ScenarioTemplate
{
    /** @var ScenarioTemplateId */
    private $id;

    /** @var ResearcherId */
    private $author;

    /** @var string */
    private $name;

    /** @var SequenceCollection */
    private $sequences;

    /**
     * ScenarioTemplate constructor.
     *
     * @param ScenarioTemplateId $id
     * @param ResearcherId       $author
     * @param string             $name
     *
     * @throws AssertionFailedException
     */
    public function __construct(ScenarioTemplateId $id, ResearcherId $author, $name)
    {
        $this->id = $id;
        $this->author = $author;
        $this->name = $name;
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
     * @param string $name
     */
    public function changeName($name)
    {
        $this->name = $name;
    }

    /**
     * @param ResearcherId $author
     */
    public function changeAuthor(ResearcherId $author)
    {
        $this->author = $author;
    }

    /**
     * @param SequenceCollection $sequences
     */
    public function setSequences(SequenceCollection $sequences)
    {
        $this->sequences = $sequences;
    }

    /**
     * @param Sequence $sequence
     *
     * @throws ScenarioException
     * @throws AssertionFailedException
     */
    public function addSequence(Sequence $sequence)
    {
        $this->sequences->add($sequence);
    }

    /**
     * @param int      $position
     * @param Sequence $sequence
     *
     * @throws AssertionFailedException
     * @throws ScenarioException
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
     *
     * @throws ScenarioException
     * @throws AssertionFailedException
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
        return new Scenario($this->sequences);
    }

    /**
     * @param $position
     *
     * @throws ScenarioException
     * @throws AssertionFailedException
     */
    private function ensureSequenceExistsAtPosition($position)
    {
        ScenarioAssertion::notNull($this->sequences->get($position), 'No sequence at this position.');
    }
}
