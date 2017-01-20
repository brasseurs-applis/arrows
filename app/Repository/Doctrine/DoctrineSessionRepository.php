<?php

namespace BrasseursApplis\Arrows\App\Repository\Doctrine;

use BrasseursApplis\Arrows\Id\SessionId;
use BrasseursApplis\Arrows\Repository\SessionRepository;
use BrasseursApplis\Arrows\Session;
use Doctrine\ORM\EntityRepository;

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
     */
    public function persist(Session $session)
    {
        $this->getEntityManager()->persist($session);
    }

    /**
     * @param Session $session
     *
     * @return void
     */
    public function delete(Session $session)
    {
        $this->getEntityManager()->remove($session);
    }
}
