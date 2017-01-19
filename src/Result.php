<?php

namespace BrasseursApplis\Arrows;

use BrasseursApplis\Arrows\VO\Duration;
use BrasseursApplis\Arrows\VO\Orientation;
use BrasseursApplis\Arrows\VO\Sequence;
use Ramsey\Uuid\Uuid;

class Result
{
    /** @var string */
    private $id;

    /** @var Session */
    private $session;

    /** @var Sequence */
    private $sequence;

    /** @var Orientation */
    private $orientation;

    /** @var Duration */
    private $duration;

    /**
     * Result constructor.
     *
     * @param Session     $session
     * @param Sequence    $sequence
     * @param Orientation $orientation
     * @param Duration    $duration
     */
    public function __construct(
        Session $session,
        Sequence $sequence,
        Orientation $orientation,
        Duration $duration
    ) {
        $this->id = Uuid::uuid4();
        $this->session = $session;
        $this->sequence = $sequence;
        $this->orientation = $orientation;
        $this->duration = $duration;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
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
