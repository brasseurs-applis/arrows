<?php

namespace BrasseursApplis\Arrows\Test\Unit\Domain\VO;

use BrasseursApplis\Arrows\VO\Orientation;

class OrientationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldBeAbleToSetARightOrientation()
    {
        $orientation = Orientation::right();

        $this->assertEquals(Orientation::RIGHT, (string) $orientation);
    }

    /**
     * @test
     */
    public function itShouldBeAbleToSetALeftOrientation()
    {
        $orientation = Orientation::left();

        $this->assertEquals(Orientation::LEFT, (string) $orientation);
    }
}
