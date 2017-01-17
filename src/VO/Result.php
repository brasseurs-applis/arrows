<?php

namespace BrasseursApplis\Arrows\VO;

class Result implements \JsonSerializable
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
            'sequence' => $this->sequence,
            'orientation' => $this->orientation,
            'duration' => $this->duration
        ];
    }

    /**
     * @param array $array
     *
     * @return Result
     */
    public static function fromJsonArray(array $array)
    {
        return new Result(
            Sequence::fromJsonArray($array['sequence']),
            new Orientation($array['orientation']),
            Duration::fromJsonArray($array['duration'])
        );
    }
}
