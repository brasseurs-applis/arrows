<?php

namespace BrasseursApplis\Arrows\VO;

use Assert\Assertion;

class MillisecondTimestamp
{
    /** @var int */
    private $timestamp;

    /**
     * MillisecondTimestamp constructor.
     *
     * @param int $timestamp
     */
    public function __construct($timestamp)
    {
        $this->timestamp = $timestamp;

        $this->guardTimestamp();
    }

    /**
     * @param MillisecondTimestamp $otherTimestamp
     *
     * @return int
     */
    public function difference(MillisecondTimestamp $otherTimestamp)
    {
        return $this->timestamp - $otherTimestamp->timestamp;
    }

    /**
     * @throws  \InvalidArgumentException
     */
    private function guardTimestamp()
    {
        Assertion::integer($this->timestamp, 'Timestamp must be an integer');
        Assertion::greaterOrEqualThan($this->timestamp, 0, 'Timestamp must be greater than zero.');
    }
}
