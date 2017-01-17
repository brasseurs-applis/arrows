<?php

namespace BrasseursApplis\Arrows\Repository;

use BrasseursApplis\Arrows\Id\SessionId;
use BrasseursApplis\Arrows\Session;

interface SessionRepository
{
    /**
     * @param SessionId $id
     *
     * @return Session
     */
    public function get(SessionId $id);

    /**
     * @param Session $session
     *
     * @return void
     */
    public function persist(Session $session);

    /**
     * @param Session $session
     *
     * @return void
     */
    public function delete(Session $session);
}
