<?php

namespace BrasseursApplis\Arrows\VO;

use Assert\Assertion;

class Orientation implements \JsonSerializable
{
    const RIGHT = 'right';
    const LEFT = 'left';
    const NULL = null;

    /** @var string */
    private $orientation;

    /**
     * Orientation constructor.
     *
     * @param string $orientation
     */
    public function __construct($orientation)
    {
        Assertion::inArray($orientation, [self::LEFT, self::RIGHT, self::NULL]);

        $this->orientation = $orientation;
    }

    /**
     * @return Orientation
     */
    public static function right()
    {
        return new self(self::RIGHT);
    }

    /**
     * @return Orientation
     */
    public static function left()
    {
        return new self(self::LEFT);
    }

    /**
     * @return Orientation
     */
    public static function null()
    {
        return new self(self::NULL);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->orientation;
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
        return $this->orientation;
    }
}
