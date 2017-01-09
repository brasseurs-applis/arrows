<?php

namespace BrasseursDApplis\Arrows\Test\Unit\Domain\VO;

use BrasseursDApplis\Arrows\VO\MillisecondTimestamp;
use Faker\Factory;

class MillisecondTimestampTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Init
     */
    public function setUp()
    {
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
    public function itShouldOnlyAcceptIntegerValues()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        new MillisecondTimestamp('a');
    }

    /**
     * @test
     */
    public function itShouldOnlyAcceptPositiveValues()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        new MillisecondTimestamp(-5);
    }

    /**
     * @test
     */
    public function itShouldAllowToCalculateTheDifferenceBetweenTwoTimestamps()
    {
        $factory = Factory::create();

        $one = $factory->randomNumber();
        $two = $factory->randomNumber();

        $first = new MillisecondTimestamp($one);
        $second = new MillisecondTimestamp($two);

        $difference = $second->difference($first);

        $this->assertEquals($two-$one, $difference);
    }
}
