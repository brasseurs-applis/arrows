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
     * Converts a value from its PHP representation to its database representation
     * of this type.
     *
     * @param mixed            $value    The value to convert.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return mixed The database representation of the value.
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string) $value ? : null;
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
