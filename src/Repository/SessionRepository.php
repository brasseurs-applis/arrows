<?php

namespace BrasseursDApplis\Arrows\Repository;

use BrasseursDApplis\Arrows\Id\SessionId;
use BrasseursDApplis\Arrows\Session;

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
