<?php

namespace BrasseursApplis\Arrows\App\Doctrine;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\Id\SessionId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class SessionIdType extends GuidType
{
    const SESSION_ID = 'session_id';

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::SESSION_ID;
    }

    /**
     * @param  string           $value
     * @param  AbstractPlatform $platform
     *
     * @return SessionId
     *
     * @throws AssertionFailedException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return new SessionId($value);
    }

    /**
     * @param  SessionId     $value
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
