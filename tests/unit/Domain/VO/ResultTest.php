<?php

namespace BrasseursApplis\Arrows\Test\Unit\Domain\VO;

use BrasseursApplis\Arrows\VO\Duration;
use BrasseursApplis\Arrows\VO\Orientation;
use BrasseursApplis\Arrows\VO\Result;
use BrasseursApplis\Arrows\VO\Sequence;

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
