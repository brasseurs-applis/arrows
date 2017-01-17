<?php

namespace BrasseursApplis\Arrows\VO;

use Assert\Assertion;
use Doctrine\Common\Collections\ArrayCollection;

class ResultCollection extends ArrayCollection implements \JsonSerializable
{
    /**
     * ResultCollection constructor.
     *
     * @param Result[] $sequences
     */
    public function __construct(array $sequences = [])
    {
        parent::__construct();

        foreach ($sequences as $index => $sequence) {
            $this->set($index, $sequence);
        }
    }

    /**
     * @param Result $value
     *
     * @return bool
     */
    public function add($value)
    {
        Assertion::isInstanceOf($value, Result::class);

        return parent::add($value);
    }

    /**
     * @param int|string $key
     * @param Result     $value
     */
    public function set($key, $value)
    {
        Assertion::isInstanceOf($value, Result::class);

        parent::set($key, $value);
    }

    /**
     * @param int|string $key
     *
     * @return Result|null
     */
    public function get($key)
    {
        return parent::get($key);
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
        return $this->getValues();
    }

    /**
     * @param array $array
     *
     * @return ResultCollection
     */
    public static function fromJsonArray(array $array)
    {
        $resultCollection = new self();

        foreach ($array as $serializedResult) {
            $resultCollection->add(Result::fromJsonArray($serializedResult));
        }

        return $resultCollection;
    }
}
