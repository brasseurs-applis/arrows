<?php

namespace BrasseursDApplis\Arrows\VO;

class Orientation
{
    const RIGHT = 'right';
    const LEFT = 'left';

    /** @var string */
    private $orientation;

    /**
     * Orientation constructor.
     *
     * @param string $orientation
     */
    private function __construct($orientation)
    {
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
     * @return string
     */
    function __toString()
    {
        return $this->orientation;
    }
}
