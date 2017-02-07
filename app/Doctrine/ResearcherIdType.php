<?php

namespace BrasseursApplis\Arrows\App\Doctrine;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\Id\ResearcherId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class ResearcherIdType extends GuidType
{
    const RESEARCHER_ID = 'researcher_id';

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::RESEARCHER_ID;
    }

    /**
     * @param  string           $value
     * @param  AbstractPlatform $platform
     *
     * @return ResearcherId
     *
     * @throws AssertionFailedException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return new ResearcherId($value);
    }

    /**
     * @param  ResearcherId     $value
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
