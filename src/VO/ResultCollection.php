<?php

namespace BrasseursDApplis\Arrows\VO;

use Assert\Assertion;
use Doctrine\Common\Collections\ArrayCollection;

class ResultCollection extends ArrayCollection
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
}
