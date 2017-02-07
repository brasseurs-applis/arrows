<?php

namespace BrasseursApplis\Arrows\App\Finder;

use BrasseursApplis\Arrows\App\DTO\ScenarioDTO;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ScenarioFinder extends BaseFinder
{
    /**
     * @param mixed $id
     * @param int   $lockMode
     * @param int   $lockVersion
     *
     * @return ScenarioDTO
     *
     * @throws ORMInvalidArgumentException
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    /**
     * @param array $sortBy
     * @param int   $pageNumber
     * @param int   $numberByPage
     *
     * @return Paginator
     *
     * @throws \OutOfBoundsException
     */
    public function getPaginatedScenarios(array $sortBy = [], $pageNumber = 1, $numberByPage = 20)
    {
        return parent::getPaginatedScenarios($sortBy, $pageNumber, $numberByPage);
    }
}
