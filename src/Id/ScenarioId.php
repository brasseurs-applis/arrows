<?php

namespace BrasseursDApplis\Arrows\Id;

use Assert\Assertion;

class ScenarioId
{
    /** @var string */
    private $id;

    /**
     * ScenarioId constructor.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        Assertion::uuid($id);

        $this->id = (string) $id;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->id;
    }
}
