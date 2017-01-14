<?php

namespace BrasseursDApplis\Arrows\App\Repository;

use BrasseursDApplis\Arrows\Id\SessionId;
use BrasseursDApplis\Arrows\Repository\SessionRepository;
use BrasseursDApplis\Arrows\Session;

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
     * @param Session $scenarioTemplate
     *
     * @return void
     */
    public function persist(Session $scenarioTemplate)
    {
        $this->sessions[(string) $scenarioTemplate->getId()] = $scenarioTemplate;
    }

    /**
     * @param Session $scenarioTemplate
     *
     * @return void
     */
    public function delete(Session $scenarioTemplate)
    {
        if (! isset($this->sessions[(string) $scenarioTemplate->getId()])) {
            return;
        }

        unset($this->sessions[(string) $scenarioTemplate->getId()]);
    }
}
