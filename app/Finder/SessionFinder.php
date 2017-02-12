<?php

namespace BrasseursApplis\Arrows\App\Finder;

use Doctrine\ORM\Tools\Pagination\Paginator;

class SessionFinder extends BaseFinder
{
    /**
     * @param array $sortBy
     * @param int   $pageNumber
     * @param int   $numberByPage
     *
     * @return Paginator
     *
     * @throws \OutOfBoundsException
     */
    public function getPaginatedSessions(array $sortBy = [], $pageNumber = 1, $numberByPage = 20)
    {
        return parent::getPaginatedEntities($sortBy, $pageNumber, $numberByPage);
    }
}
