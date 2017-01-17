<?php

namespace BrasseursApplis\Arrows\Id;

use Assert\Assertion;

class UserId implements \JsonSerializable
{
    /** @var string */
    private $id;

    /**
     * SessionId constructor.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        Assertion::uuid($id);

        $this->id = (string) $id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id;
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
        return $this->id;
    }
}
