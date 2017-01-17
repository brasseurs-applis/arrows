<?php

namespace BrasseursApplis\Arrows\VO;

use Assert\Assertion;

class Duration implements \JsonSerializable
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
            'start' => $this->start,
            'end' => $this->end,
            'duration' => $this->duration
        ];
    }

    /**
     * @param array $array
     *
     * @return Duration
     */
    public static function fromJsonArray(array $array)
    {
        return new self($array['start'], $array['end']);
    }
}
