<?php

namespace BrasseursApplis\Arrows\App\Doctrine;

use BrasseursApplis\Arrows\VO\Orientation;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class OrientationType extends StringType
{
    const ORIENTATION = 'orientation';

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::ORIENTATION;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return Orientation
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Orientation(parent::convertToPHPValue($value, $platform));
    }
}
