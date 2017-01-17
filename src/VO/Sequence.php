<?php

namespace BrasseursApplis\Arrows\VO;

class Sequence implements \JsonSerializable
{
    /** @var Position */
    private $position;

    /** @var Orientation */
    private $previewOrientation;

    /** @var Orientation */
    private $initiationOrientation;

    /** @var Orientation */
    private $mainOrientation;

    /**
     * Sequence constructor.
     *
     * @param Position    $position
     * @param Orientation $previewOrientation
     * @param Orientation $initiationOrientation
     * @param Orientation $mainOrientation
     */
    public function __construct(
        Position $position,
        Orientation $previewOrientation,
        Orientation $initiationOrientation,
        Orientation $mainOrientation
    ) {
        $this->position = $position;
        $this->previewOrientation = $previewOrientation;
        $this->initiationOrientation = $initiationOrientation;
        $this->mainOrientation = $mainOrientation;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return Orientation
     */
    public function getPreviewOrientation()
    {
        return $this->previewOrientation;
    }

    /**
     * @return mixed
     */
    public function getInitiationOrientation()
    {
        return $this->initiationOrientation;
    }

    /**
     * @return mixed
     */
    public function getMainOrientation()
    {
        return $this->mainOrientation;
    }

    /**
     * @return bool
     */
    public function isCongruentWithPreview()
    {
        return $this->previewOrientation == $this->mainOrientation;
    }

    /**
     * @return bool
     */
    public function isCongruentWithInitiation()
    {
        return $this->initiationOrientation == $this->mainOrientation;
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
            'position' => $this->position,
            'previewOrientation' => $this->previewOrientation,
            'initiationOrientation' => $this->initiationOrientation,
            'mainOrientation' => $this->mainOrientation
        ];
    }

    /**
     * @param array $array
     *
     * @return Sequence
     */
    public static function fromJsonArray(array $array)
    {
        return new Sequence(
            new Position($array['position']),
            new Orientation($array['previewOrientation']),
            new Orientation($array['initiationOrientation']),
            new Orientation($array['mainOrientation'])
        );
    }
}
