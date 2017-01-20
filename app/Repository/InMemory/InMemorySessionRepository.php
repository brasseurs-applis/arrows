<?php

namespace BrasseursApplis\Arrows\App\Repository\InMemory;

use BrasseursApplis\Arrows\Id\SessionId;
use BrasseursApplis\Arrows\Repository\SessionRepository;
use BrasseursApplis\Arrows\Session;

class InMemorySessionRepository implements SessionRepository
{
    /** @var Session[] */
    private $sessions;

    /**
     * @param SessionId $id
     *
     * @return Session
     */
    public function get(SessionId $id)
    {
        if (! isset($this->sessions[(string) $id])) {
            return null;
        }

        return $this->sessions[(string) $id];
    }

    /**
     * @param Session $session
     *
     * @return void
     */
    public function persist(Session $session)
    {
        $this->sessions[(string) $session->getId()] = $session;
    }

    /**
     * @param Session $session
     *
     * @return void
     */
    public function delete(Session $session)
    {
        if (! isset($this->sessions[(string) $session->getId()])) {
            return;
        }

        unset($this->sessions[(string) $session->getId()]);
    }
}
