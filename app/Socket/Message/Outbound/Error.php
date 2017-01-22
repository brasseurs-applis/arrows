<?php

namespace BrasseursApplis\Arrows\App\Socket\Message\Outbound;

use BrasseursApplis\Arrows\App\Message\Message;

class Error implements Message, \JsonSerializable
{
    const TYPE = 'error';

    /** @var string */
    private $message;

    /**
     * Error constructor.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

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
        return [
            'type' => self::TYPE,
            'message' => $this->message
        ];
    }
}
