<?php

namespace BrasseursDApplis\Arrows\Test\Unit\Domain\VO;

use BrasseursDApplis\Arrows\VO\Orientation as O;
use BrasseursDApplis\Arrows\VO\Position as P;
use BrasseursDApplis\Arrows\VO\Result;
use BrasseursDApplis\Arrows\VO\ResultCollection;

class ResultCollectionTest extends \PHPUnit_Framework_TestCase
{
    /** @var Result */
    private $firstResult;

    /** @var Result */
    private $secondResult;

    /** @var Result */
    private $thirdResult;

    /**
     * Init
     */
    public function setUp()
    {
        $this->firstResult = \Mockery::mock(Result::class);
        $this->secondResult = \Mockery::mock(Result::class);
        $this->thirdResult = \Mockery::mock(Result::class);
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
    public function itShouldBePossibleToBuildACollectionFromAnArrayOfResults()
    {
        $collection = new ResultCollection(
            [ $this->firstResult, $this->secondResult, $this->thirdResult ]
        );

        $this->assertEquals($this->firstResult, $collection->get(0));
        $this->assertEquals($this->secondResult, $collection->get(1));
        $this->assertEquals($this->thirdResult, $collection->get(2));
    }

    /**
     * @test
     */
    public function itShouldNotBePossibleToBuildACollectionFromAnArrayOfOtherThanResult()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        new ResultCollection([null]);
    }

    /**
     * @test
     */
    public function itShouldBePossibleToAddResultToTheCollection()
    {
        $collection = new ResultCollection();
        $collection->add($this->firstResult);

        $this->assertEquals($this->firstResult, $collection->get(0));
    }

    /**
     * @test
     */
    public function itShouldNotBePossibleToAddOtherThanResultToTheCollection()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $collection = new ResultCollection();
        $collection->add(null);
    }

    /**
     * @test
     */
    public function itShouldBePossibleToSetResultToTheCollection()
    {
        $collection = new ResultCollection();
        $collection->set(5, $this->firstResult);

        $this->assertEquals($this->firstResult, $collection->get(5));
    }

    /**
     * @test
     */
    public function itShouldNotBePossibleToSetOtherThanResultToTheCollection()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $collection = new ResultCollection();
        $collection->set(0, null);
    }
}
