<?php

namespace BrasseursApplis\Arrows\App\Doctrine;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\App\DTO\Helper\SequenceConverter;
use BrasseursApplis\Arrows\App\DTO\SequenceDTO;
use BrasseursApplis\Arrows\VO\SequenceCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonArrayType;

class SequencesType extends JsonArrayType
{
    const SEQUENCES = 'sequences';

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::SEQUENCES;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return SequenceDTO[]
     *
     * @throws AssertionFailedException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return SequenceConverter::fromJsonArray(parent::convertToPHPValue($value, $platform));
    }
}
