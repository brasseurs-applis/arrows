<?php

namespace BrasseursApplis\Arrows\App\Socket\Message\Outbound;

use BrasseursApplis\Arrows\App\Socket\Message;
use BrasseursApplis\Arrows\VO\Sequence;

class SessionSequence implements Message, \JsonSerializable
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
        $this->position = (string) $sequence->getPosition() ? : null;
        $this->previewOrientation = (string) $sequence->getPreviewOrientation() ? : null;
        $this->initiationOrientation = (string) $sequence->getInitiationOrientation() ? : null;
        $this->mainOrientation = (string) $sequence->getMainOrientation() ? : null;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
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
