<?php

namespace BrasseursApplis\Arrows\VO;

class Sequence
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
}
