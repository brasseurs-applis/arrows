<?php

namespace BrasseursDApplis\Arrows\App\Message;

use BrasseursDApplis\Arrows\Session;
use BrasseursDApplis\Arrows\VO\Sequence;

class SessionSequence implements \JsonSerializable
{
    const TYPE = 'session.sequence';

    /** @var string */
    private $position;

    /** @var string */
    private $previewOrientation;

    /** @var string */
    private $initiationOrientation;

    /** @var string */
    private $mainOrientation;

    /**
     * Sequence constructor.
     *
     * @param Sequence $sequence
     */
    public function __construct(Sequence $sequence) {
        $this->position = (string) $sequence->getPosition();
        $this->previewOrientation = (string) $sequence->getPreviewOrientation();
        $this->initiationOrientation = (string) $sequence->getInitiationOrientation();
        $this->mainOrientation = (string) $sequence->getMainOrientation();
    }


    /**
     * @return array
     */
    function jsonSerialize()
    {
        return [
            'type' => self::TYPE,
            'position' => $this->position,
            'previewOrientation' => $this->previewOrientation,
            'initiationOrientation' => $this->initiationOrientation,
            'mainOrientation' => $this->mainOrientation
        ];
    }
}
