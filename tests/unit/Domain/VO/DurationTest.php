<?php

namespace BrasseursApplis\Arrows\Test\Unit\Domain\VO;

use BrasseursApplis\Arrows\VO\Duration;
use BrasseursApplis\Arrows\VO\MillisecondTimestamp;
use Faker\Factory;

class DurationTest extends \PHPUnit_Framework_TestCase
{
    /** @var MillisecondTimestamp */
    private $startMs;

    /** @var MillisecondTimestamp */
    private $endMs;

    /**
     * Init
     */
    public function setUp()
    {
        $faker = Factory::create();

        $number = $faker->randomNumber();
        $this->startMs = new MillisecondTimestamp($faker->numberBetween(0, $number));
        $this->endMs = new MillisecondTimestamp($faker->numberBetween($number));
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
    public function itShouldNotAcceptAStartDateAfterTheEndDate()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        new Duration($this->endMs, $this->startMs);
    }

    /**
     * @test
     */
    public function itShouldAcceptAStartDateBeforeTheEndDate()
    {
        $duration = new Duration($this->startMs, $this->endMs);
        $durationMs = $duration->getDuration();
        $this->assertGreaterThanOrEqual(0, $durationMs);
    }
}
