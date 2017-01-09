<?php

namespace BrasseursDApplis\Arrows\VO;

use Assert\Assertion;

class Duration
{
    /** @var MillisecondTimestamp */
    private $start;

    /** @var MillisecondTimestamp */
    private $end;

    /** @var int */
    private $duration;

    /**
     * Duration constructor.
     *
     * @param MillisecondTimestamp $start
     * @param MillisecondTimestamp $end
     */
    public function __construct(MillisecondTimestamp $start, MillisecondTimestamp $end)
    {
        $this->start = $start;
        $this->end = $end;
        $this->duration = $this->end->difference($start);

        $this->guardDuration();
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    private function guardDuration()
    {
        Assertion::greaterOrEqualThan($this->duration, 0, 'Start timestamp must be prior to end timestamp');
    }
}
