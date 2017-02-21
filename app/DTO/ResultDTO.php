<?php

namespace BrasseursApplis\Arrows\App\DTO;

class ResultDTO
{
    /** @var string */
    private $id;

    /** @var SessionDTO */
    private $session;

    /** @var string */
    private $position;

    /** @var string */
    private $previewOrientation;

    /** @var string */
    private $initiationOrientation;

    /** @var string */
    private $mainOrientation;

    /** @var string */
    private $answerOrientation;

    /** @var int */
    private $start;

    /** @var int */
    private $end;

    /**
     * ResultDTO constructor.
     *
     * @param string $id
     * @param SessionDTO $session
     * @param string $position
     * @param string $previewOrientation
     * @param string $initiationOrientation
     * @param string $mainOrientation
     * @param string $answerOrientation
     * @param int $start
     * @param int $end
     */
    public function __construct(
        $id,
        SessionDTO $session,
        $position,
        $previewOrientation,
        $initiationOrientation,
        $mainOrientation,
        $answerOrientation,
        $start,
        $end
    ) {
        $this->id = $id;
        $this->session = $session;
        $this->position = $position;
        $this->previewOrientation = $previewOrientation;
        $this->initiationOrientation = $initiationOrientation;
        $this->mainOrientation = $mainOrientation;
        $this->answerOrientation = $answerOrientation;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return SessionDTO
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getPreviewOrientation()
    {
        return $this->previewOrientation;
    }

    /**
     * @return string
     */
    public function getInitiationOrientation()
    {
        return $this->initiationOrientation;
    }

    /**
     * @return string
     */
    public function getMainOrientation()
    {
        return $this->mainOrientation;
    }

    /**
     * @return string
     */
    public function getAnswerOrientation()
    {
        return $this->answerOrientation;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return int
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->end - $this->start;
    }
}
