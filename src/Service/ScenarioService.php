<?php

namespace BrasseursApplis\Arrows\Service;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\Id\ResearcherId;
use BrasseursApplis\Arrows\Id\ScenarioTemplateId;
use BrasseursApplis\Arrows\Repository\ScenarioTemplateRepository;
use BrasseursApplis\Arrows\ScenarioTemplate;
use BrasseursApplis\Arrows\VO\SequenceCollection;

class ScenarioService
{
    /** @var ScenarioTemplateRepository */
    private $scenarioRepository;

    /**
     * ScenarioService constructor.
     *
     * @param ScenarioTemplateRepository $scenarioRepository
     */
    public function __construct(ScenarioTemplateRepository $scenarioRepository)
    {
        $this->scenarioRepository = $scenarioRepository;
    }

    /**
     * @param ScenarioTemplateId $id
     * @param ResearcherId       $researcherId
     * @param string             $name
     * @param SequenceCollection $sequences
     *
     * @throws AssertionFailedException
     */
    public function createScenario(
        ScenarioTemplateId $id,
        ResearcherId $researcherId,
        $name,
        SequenceCollection $sequences
    ) {
        $scenario = new ScenarioTemplate($id, $researcherId, $name);

        $scenario->setSequences($sequences);

        $this->scenarioRepository->persist($scenario);
    }

    /**
     * @param ScenarioTemplateId $id
     * @param ResearcherId       $researcherId
     * @param string             $name
     * @param SequenceCollection $sequences
     *
     * @throws AssertionFailedException
     */
    public function updateScenario(
        ScenarioTemplateId $id,
        ResearcherId $researcherId,
        $name,
        SequenceCollection $sequences
    ) {
        $scenario = $this->scenarioRepository->get($id);

        $scenario->changeName($name);
        $scenario->changeAuthor($researcherId);
        $scenario->setSequences($sequences);

        $this->scenarioRepository->persist($scenario);
    }
}
