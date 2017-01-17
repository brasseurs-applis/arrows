<?php

namespace BrasseursApplis\Arrows\App\Doctrine;

use BrasseursApplis\Arrows\Id\SubjectId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class SubjectIdType extends GuidType
{
    const SUBJECT_ID = 'subject_id';

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::SUBJECT_ID;
    }

    /**
     * @param  string           $value
     * @param  AbstractPlatform $platform
     * @return SubjectId
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return new SubjectId($value);
    }

    /**
     * @param  SubjectId     $value
     * @param  AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return (string) $value;
    }
}
