<?php

namespace BrasseursApplis\Arrows\VO;

class Result
{
    /** @var Sequence */
    private $sequence;

    /** @var Orientation */
    private $orientation;

    /** @var Duration */
    private $duration;

    /**
     * Result constructor.
     *
     * @param Sequence    $sequence
     * @param Orientation $orientation
     * @param Duration    $duration
     */
    public function __construct(Sequence $sequence, Orientation $orientation, Duration $duration)
    {
        $this->sequence = $sequence;
        $this->orientation = $orientation;
        $this->duration = $duration;
    }

    /**
     * @return Sequence
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @return Orientation
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * @return Duration
     */
    public function getDuration()
    {
        return $this->duration;
    }
}
