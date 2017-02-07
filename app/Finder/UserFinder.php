<?php

namespace BrasseursApplis\Arrows\App\Finder;

use BrasseursApplis\Arrows\App\DTO\UserDTO;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserFinder extends EntityRepository
{
    /**
     * @param mixed $id
     * @param int   $lockMode
     * @param int   $lockVersion
     *
     * @return UserDTO
     *
     * @throws ORMInvalidArgumentException
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        /** @var UserDTO $user */
        $user = parent::find($id, $lockMode, $lockVersion);
        $this->detach($user);

        return $user;
    }

    /**
     * @param string $userName
     *
     * @return UserDTO
     *
     * @throws ORMInvalidArgumentException
     */
    public function findByUserName($userName)
    {
        /** @var UserDTO $user */
        $user = $this->findOneBy(['userName' => $userName]);
        $this->detach($user);

        return $user;
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
    public function getPaginatedUsers(array $sortBy = [], $pageNumber = 1, $numberByPage = 20)
    {
        $alias = 'user';

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
     * @param UserDTO $user
     *
     * @throws ORMInvalidArgumentException
     */
    private function detach($user = null)
    {
        if ($user === null) {
            return;
        }

        $this->getEntityManager()->detach($user);
    }
}
