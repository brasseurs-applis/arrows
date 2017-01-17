<?php

namespace BrasseursApplis\Arrows\App\Repository;

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
