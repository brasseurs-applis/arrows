<?php

namespace BrasseursApplis\Arrows\VO;

use Assert\Assertion;
use Assert\AssertionFailedException;

class MillisecondTimestamp implements \JsonSerializable
{
    /** @var int */
    private $timestamp;

    /**
     * MillisecondTimestamp constructor.
     *
     * @param int $timestamp
     *
     * @throws AssertionFailedException
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
     * @throws AssertionFailedException
     */
    private function guardTimestamp()
    {
        Assertion::integer($this->timestamp, 'Timestamp must be an integer');
        Assertion::greaterOrEqualThan($this->timestamp, 0, 'Timestamp must be greater than zero.');
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
        return $this->timestamp;
    }
}
