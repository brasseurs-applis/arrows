<?php

namespace BrasseursApplis\Arrows\Test\Unit\Domain\VO;

use BrasseursApplis\Arrows\VO\Position;

class PositionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldBeAbleToSetATopPosition()
    {
        $position = Position::top();

        $this->assertEquals(Position::TOP, (string) $position);
    }

    /**
     * @test
     */
    public function itShouldBeAbleToSetABottomPosition()
    {
        $position = Position::bottom();

        $this->assertEquals(Position::BOTTOM, (string) $position);
    }
}
