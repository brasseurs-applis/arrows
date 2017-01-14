<?php

namespace BrasseursDApplis\Arrows\Id;

use Assert\Assertion;

class SubjectId
{
    /** @var string */
    private $id;

    /**
     * SessionId constructor.
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
