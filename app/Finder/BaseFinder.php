<?php

namespace BrasseursApplis\Arrows\App\Finder;

use BrasseursApplis\Arrows\App\DTO\ScenarioDTO;
use BrasseursApplis\Arrows\App\DTO\UserDTO;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BaseFinder extends EntityRepository
{
    /**
     * @param mixed $id
     * @param int   $lockMode
     * @param int   $lockVersion
     *
     * @return mixed
     *
     * @throws ORMInvalidArgumentException
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        $dto = parent::find($id, $lockMode, $lockVersion);
        $this->detach($dto);

        return $dto;
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
        $alias = 'e';

        $queryBuilder = $this->createQueryBuilder($alias);
        $queryBuilder->select($alias)
            ->setFirstResult(($pageNumber - 1) * $numberByPage)
            ->setMaxResults($numberByPage);

        foreach($sortBy as $field => $direction) {
            if (!in_array($direction, ['ASC', 'DESC'], true)) {
                $direction = 'ASC';
            }
            $queryBuilder->addOrderBy($alias.'.'.$field, $direction);
        }

        $paginator = new Paginator($queryBuilder);

        if ($pageNumber !== 1 && $paginator->getIterator()->count() === 0) {
            throw new \OutOfBoundsException();
        }

        return $paginator;
    }

    /**
     * @param mixed $dto
     *
     * @throws ORMInvalidArgumentException
     */
    protected function detach($dto = null)
    {
        if ($dto === null) {
            return;
        }

        $this->getEntityManager()->detach($dto);
    }
}
