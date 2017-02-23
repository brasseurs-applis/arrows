<?php

namespace BrasseursApplis\Arrows\App\Doctrine;

use BrasseursApplis\Arrows\VO\Orientation;
use BrasseursApplis\Arrows\VO\Position;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class PositionType extends StringType
{
    const POSITION = 'position';

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::POSITION;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return Position
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Position(parent::convertToPHPValue($value, $platform));
    }
}
