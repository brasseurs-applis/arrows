<?php

namespace BrasseursDApplis\Arrows\VO;

use Assert\Assertion;
use Doctrine\Common\Collections\ArrayCollection;

class SequenceCollection extends ArrayCollection
{
    /**
     * SequenceCollection constructor.
     *
     * @param Sequence[] $sequences
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
     */
    public function add($value)
    {
        Assertion::isInstanceOf($value, Sequence::class);

        return parent::add($value);
    }

    /**
     * @param int|string $key
     * @param Sequence   $value
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
}
