<?php

namespace BrasseursApplis\Arrows\App\DTO\Helper;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\App\DTO\SequenceDTO;
use BrasseursApplis\Arrows\VO\Orientation;
use BrasseursApplis\Arrows\VO\Position;
use BrasseursApplis\Arrows\VO\Sequence;
use BrasseursApplis\Arrows\VO\SequenceCollection;

class SequenceConverter
{
    /**
     * @param SequenceDTO[] $sequences
     *
     * @return SequenceCollection
     *
     * @throws AssertionFailedException
     */
    public static function toSequenceCollection(array $sequences)
    {
        return new SequenceCollection(array_map(function (SequenceDTO $sequence) {
            return self::toSequence($sequence);
        }, $sequences));
    }

    /**
     * @param array $array
     *
     * @return SequenceDTO[]
     */
    public static function fromJsonArray(array $array)
    {
        return array_map(function (array $array) {
            return new SequenceDTO(
                $array['position'],
                $array['previewOrientation'],
                $array['initiationOrientation'],
                $array['mainOrientation']
            );
        }, $array);
    }

    /**
     * @param SequenceDTO $sequenceDTO
     *
     * @return Sequence
     */
    private static function toSequence(SequenceDTO $sequenceDTO)
    {
        return new Sequence(
            new Position($sequenceDTO->getPosition()),
            new Orientation($sequenceDTO->getPreviewOrientation()),
            new Orientation($sequenceDTO->getInitiationOrientation()),
            new Orientation($sequenceDTO->getMainOrientation())
        );
    }
}
