<?php

namespace BrasseursDApplis\Arrows\Test\Unit\Domain\VO;

use BrasseursDApplis\Arrows\VO\Duration;
use BrasseursDApplis\Arrows\VO\Orientation;
use BrasseursDApplis\Arrows\VO\Result;
use BrasseursDApplis\Arrows\VO\Sequence;

class ResultTest extends \PHPUnit_Framework_TestCase
{
    /** @var Sequence */
    private $sequence;

    /** @var Orientation */
    private $orientation;

    /** @var Duration */
    private $duration;

    /** @var Result */
    private $serviceUnderTest;

    /**
     * Init
     */
    public function setUp()
    {
        $this->sequence = \Mockery::mock(Sequence::class);
        $this->orientation = \Mockery::mock(Orientation::class);
        $this->duration = \Mockery::mock(Duration::class);

        $this->serviceUnderTest = new Result($this->sequence, $this->orientation, $this->duration);
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
    public function itShouldAccessSequenceAndOrientationAndDuration()
    {
        $this->assertEquals($this->sequence, $this->serviceUnderTest->getSequence());
        $this->assertEquals($this->orientation, $this->serviceUnderTest->getOrientation());
        $this->assertEquals($this->duration, $this->serviceUnderTest->getDuration());
    }
}
