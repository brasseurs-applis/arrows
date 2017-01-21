<?php

namespace BrasseursApplis\Arrows\App\Repository\Doctrine;

use BrasseursApplis\Arrows\Id\UserId;
use BrasseursApplis\Arrows\Repository\UserRepository;
use BrasseursApplis\Arrows\User;
use Doctrine\ORM\EntityRepository;

class DoctrineUserRepository extends EntityRepository implements UserRepository
{
    /**
     * @param UserId $id
     *
     * @return User
     */
    public function get(UserId $id)
    {
        /** @var User $user */
        $user = $this->find((string) $id);

        return $user;
    }

    /**
     * @param string $userName
     *
     * @return User
     */
    public function getByUserName($userName)
    {
        /** @var User $user */
        $user = $this->findOneBy(['userName' => $userName]);

        return $user;
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function persist(User $user)
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function delete(User $user)
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }
}
