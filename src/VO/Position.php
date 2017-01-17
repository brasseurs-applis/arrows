<?php

namespace BrasseursApplis\Arrows\VO;

use Assert\Assertion;

class Position implements \JsonSerializable
{
    const TOP = 'top';
    const BOTTOM = 'bottom';

    /** @var string */
    private $position;

    /**
     * Position constructor.
     *
     * @param string $position
     */
    public function __construct($position)
    {
        Assertion::inArray($position, [self::TOP, self::BOTTOM]);

        $this->position = $position;
    }

    /**
     * @return Position
     */
    public static function top()
    {
        return new self(self::TOP);
    }

    /**
     * @return Position
     */
    public static function bottom()
    {
        return new self(self::BOTTOM);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->position;
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
        return $this->position;
    }
}
