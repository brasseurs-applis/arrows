<?php

namespace BrasseursApplis\Arrows\App\Repository\Doctrine;

use BrasseursApplis\Arrows\Id\ScenarioTemplateId;
use BrasseursApplis\Arrows\Repository\ScenarioTemplateRepository;
use BrasseursApplis\Arrows\ScenarioTemplate;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;

class DoctrineScenarioTemplateRepository extends EntityRepository implements ScenarioTemplateRepository
{
    /**
     * @param ScenarioTemplateId $id
     *
     * @return ScenarioTemplate
     */
    public function get(ScenarioTemplateId $id)
    {
        /** @var ScenarioTemplate $scenarioTemplate */
        $scenarioTemplate = $this->find((string) $id);

        return $scenarioTemplate;
    }

    /**
     * @param ScenarioTemplate $scenarioTemplate
     *
     * @return void
     *
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    public function persist(ScenarioTemplate $scenarioTemplate)
    {
        $this->getEntityManager()->persist($scenarioTemplate);
        $this->getEntityManager()->flush();
    }

    /**
     * @param ScenarioTemplate $scenarioTemplate
     *
     * @return void
     *
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    public function delete(ScenarioTemplate $scenarioTemplate)
    {
        $this->getEntityManager()->remove($scenarioTemplate);
        $this->getEntityManager()->flush();
    }
}
