<?php

namespace BrasseursDApplis\Arrows;

use BrasseursDApplis\Arrows\Id\ResearcherId;

class Researcher
{
    /** @var ResearcherId */
    private $id;

    /**
     * Researcher constructor.
     *
     * @param ResearcherId $id
     */
    public function __construct(ResearcherId $id)
    {
        $this->id = $id;
    }

    /**
     * @return ResearcherId
     */
    public function getId()
    {
        return $this->id;
    }
}
