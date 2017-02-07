<?php

namespace BrasseursApplis\Arrows\App\Doctrine;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\VO\SequenceCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonArrayType;

class SequenceCollectionType extends JsonArrayType
{
    const SEQUENCE_COLLECTION = 'sequence_collection';

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::SEQUENCE_COLLECTION;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return SequenceCollection
     *
     * @throws AssertionFailedException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return SequenceCollection::fromJsonArray(parent::convertToPHPValue($value, $platform));
    }
}
