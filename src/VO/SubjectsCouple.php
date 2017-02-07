<?php

namespace BrasseursApplis\Arrows\VO;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\Id\SubjectId;

class SubjectsCouple implements \JsonSerializable
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

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *        which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'positionOne' => $this->positionOne,
            'positionTwo' => $this->positionTwo
        ];
    }

    /**
     * @param array $array
     *
     * @return SubjectsCouple
     *
     * @throws AssertionFailedException
     */
    public static function fromJsonArray(array $array)
    {
        return new self(new SubjectId($array['positionOne']), new SubjectId($array['positionTwo']));
    }
}
