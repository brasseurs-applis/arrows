<?php

namespace BrasseursApplis\Arrows\VO;

class Position
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
    private function __construct($position)
    {
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
    function __toString()
    {
        return $this->position;
    }
}
