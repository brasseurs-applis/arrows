<?php

namespace BrasseursDApplis\Arrows\Test\Unit\Domain\VO;

use BrasseursDApplis\Arrows\VO\Orientation as O;
use BrasseursDApplis\Arrows\VO\Position as P;
use BrasseursDApplis\Arrows\VO\Sequence;
use BrasseursDApplis\Arrows\VO\SequenceCollection;

class SequenceCollectionTest extends \PHPUnit_Framework_TestCase
{
    /** @var Sequence */
    private $firstSequence;

    /** @var Sequence */
    private $secondSequence;

    /** @var Sequence */
    private $thirdSequence;

    /**
     * Init
     */
    public function setUp()
    {
        $this->firstSequence = new Sequence(P::top(), O::left(), O::right(), O::left());
        $this->secondSequence = new Sequence(P::bottom(), O::left(), O::left(), O::left());
        $this->thirdSequence = new Sequence(P::top(), O::right(), O::right(), O::left());
    }

    /**
     * Close
     */
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldBePossibleToBuildACollectionFromAnArrayOfSequences()
    {
        $collection = new SequenceCollection(
            [ $this->firstSequence, $this->secondSequence, $this->thirdSequence ]
        );

        $this->assertEquals($this->firstSequence, $collection->get(0));
        $this->assertEquals($this->secondSequence, $collection->get(1));
        $this->assertEquals($this->thirdSequence, $collection->get(2));
    }

    /**
     * @test
     */
    public function itShouldNotBePossibleToBuildACollectionFromAnArrayOfOtherThanSequence()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        new SequenceCollection([null]);
    }

    /**
     * @test
     */
    public function itShouldBePossibleToAddSequenceToTheCollection()
    {
        $collection = new SequenceCollection();
        $collection->add($this->firstSequence);

        $this->assertEquals($this->firstSequence, $collection->get(0));
    }

    /**
     * @test
     */
    public function itShouldNotBePossibleToAddOtherThanSequenceToTheCollection()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $collection = new SequenceCollection();
        $collection->add(null);
    }

    /**
     * @test
     */
    public function itShouldBePossibleToSetSequenceToTheCollection()
    {
        $collection = new SequenceCollection();
        $collection->set(5, $this->firstSequence);

        $this->assertEquals($this->firstSequence, $collection->get(5));
    }

    /**
     * @test
     */
    public function itShouldNotBePossibleToSetOtherThanSequenceToTheCollection()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $collection = new SequenceCollection();
        $collection->set(0, null);
    }
}
