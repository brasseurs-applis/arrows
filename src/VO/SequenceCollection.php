<?php

namespace BrasseursApplis\Arrows\VO;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Doctrine\Common\Collections\ArrayCollection;

class SequenceCollection extends ArrayCollection implements \JsonSerializable
{
    /**
     * SequenceCollection constructor.
     *
     * @param Sequence[] $sequences
     *
     * @throws AssertionFailedException
     */
    public function __construct(array $sequences = [])
    {
        parent::__construct();

        foreach ($sequences as $index => $sequence) {
            $this->set($index, $sequence);
        }
    }

    /**
     * @param Sequence $value
     *
     * @return bool
     *
     * @throws AssertionFailedException
     */
    public function add($value)
    {
        Assertion::isInstanceOf($value, Sequence::class);

        return parent::add($value);
    }

    /**
     * @param int|string $key
     * @param Sequence   $value
     *
     * @throws AssertionFailedException
     */
    public function set($key, $value)
    {
        Assertion::isInstanceOf($value, Sequence::class);

        parent::set($key, $value);
    }

    /**
     * @param int|string $key
     *
     * @return Sequence|null
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
     * @return SequenceCollection
     *
     * @throws AssertionFailedException
     */
    public static function fromJsonArray(array $array)
    {
        $sequenceCollection = new self();

        foreach ($array as $serializedSequence) {
            $sequenceCollection->add(Sequence::fromJsonArray($serializedSequence));
        }

        return $sequenceCollection;
    }
}
