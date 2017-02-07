<?php

namespace BrasseursApplis\Arrows\App\Doctrine;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\Id\UserId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class UserIdType extends GuidType
{
    const USER_ID = 'user_id';

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::USER_ID;
    }

    /**
     * @param  string           $value
     * @param  AbstractPlatform $platform
     *
     * @return UserId
     * @throws AssertionFailedException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return new UserId($value);
    }

    /**
     * @param  UserId     $value
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
