<?php

namespace BrasseursApplis\Arrows\App\Repository\Doctrine;

use BrasseursApplis\Arrows\Id\SessionId;
use BrasseursApplis\Arrows\Repository\SessionRepository;
use BrasseursApplis\Arrows\Session;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;

class DoctrineSessionRepository extends EntityRepository implements SessionRepository
{
    /**
     * @param SessionId $id
     *
     * @return Session
     */
    public function get(SessionId $id)
    {
        /** @var Session $session */
        $session = $this->find((string) $id);

        return $session;
    }

    /**
     * @param Session $session
     *
     * @return void
     *
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    public function persist(Session $session)
    {
        $this->getEntityManager()->persist($session);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Session $session
     *
     * @return void
     *
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    public function delete(Session $session)
    {
        $this->getEntityManager()->remove($session);
        $this->getEntityManager()->flush();
    }
}
