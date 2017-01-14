<?php

namespace BrasseursDApplis\Arrows;

use BrasseursDApplis\Arrows\Id\SubjectId;

class Subject
{
    /** @var SubjectId */
    private $id;

    /**
     * Subject constructor.
     *
     * @param SubjectId $id
     */
    public function __construct(SubjectId $id)
    {
        $this->id = $id;
    }

    /**
     * @return SubjectId
     */
    public function getId()
    {
        return $this->id;
    }
}
