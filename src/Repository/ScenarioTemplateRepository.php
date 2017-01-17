<?php

namespace BrasseursApplis\Arrows\Repository;

use BrasseursApplis\Arrows\Id\ScenarioTemplateId;
use BrasseursApplis\Arrows\ScenarioTemplate;

interface ScenarioTemplateRepository
{
    /**
     * @param ScenarioTemplateId $id
     *
     * @return ScenarioTemplate
     */
    public function get(ScenarioTemplateId $id);

    /**
     * @param ScenarioTemplate $scenarioTemplate
     *
     * @return void
     */
    public function persist(ScenarioTemplate $scenarioTemplate);

    /**
     * @param ScenarioTemplate $scenarioTemplate
     *
     * @return void
     */
    public function delete(ScenarioTemplate $scenarioTemplate);
}
