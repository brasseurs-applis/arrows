<?php

namespace BrasseursApplis\Arrows\App\Socket\Message\Outbound;

use BrasseursApplis\Arrows\App\Socket\Message;

class SessionReady implements Message, \JsonSerializable
{
    const TYPE = 'session.ready';

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *        which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [ 'type' => self::TYPE ];
    }
}
