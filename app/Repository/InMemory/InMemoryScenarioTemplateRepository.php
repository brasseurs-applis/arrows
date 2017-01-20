<?php

namespace BrasseursApplis\Arrows\App\Repository\InMemory;

use BrasseursApplis\Arrows\Id\ScenarioTemplateId;
use BrasseursApplis\Arrows\Repository\ScenarioTemplateRepository;
use BrasseursApplis\Arrows\ScenarioTemplate;

class InMemoryScenarioTemplateRepository implements ScenarioTemplateRepository
{
    /** @var ScenarioTemplate[] */
    private $scenarioTemplates;

    /**
     * @param ScenarioTemplateId $id
     *
     * @return ScenarioTemplate
     */
    public function get(ScenarioTemplateId $id)
    {
        if (! isset($this->scenarioTemplates[(string) $id])) {
            return null;
        }

        return $this->scenarioTemplates[(string) $id];
    }

    /**
     * @param ScenarioTemplate $scenarioTemplate
     *
     * @return void
     */
    public function persist(ScenarioTemplate $scenarioTemplate)
    {
        $this->scenarioTemplates[(string) $scenarioTemplate->getId()] = $scenarioTemplate;
    }

    /**
     * @param ScenarioTemplate $scenarioTemplate
     *
     * @return void
     */
    public function delete(ScenarioTemplate $scenarioTemplate)
    {
        if (! isset($this->scenarioTemplates[(string) $scenarioTemplate->getId()])) {
            return;
        }

        unset($this->scenarioTemplates[(string) $scenarioTemplate->getId()]);
    }
}
