<?php

namespace BrasseursApplis\Arrows\VO;

use BrasseursApplis\Arrows\Id\SubjectId;

class SubjectsCouple
{
    /** @var SubjectId */
    private $positionOne;

    /** @var SubjectId */
    private $positionTwo;

    /**
     * SubjectsCouple constructor.
     *
     * @param SubjectId $positionOne
     * @param SubjectId $positionTwo
     */
    public function __construct(
        SubjectId $positionOne,
        SubjectId $positionTwo
    ) {
        $this->positionOne = $positionOne;
        $this->positionTwo = $positionTwo;
    }

    /**
     * @return SubjectId
     */
    public function getPositionOne()
    {
        return $this->positionOne;
    }

    /**
     * @return SubjectId
     */
    public function getPositionTwo()
    {
        return $this->positionTwo;
    }
}
