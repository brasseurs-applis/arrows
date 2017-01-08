<?php

namespace BrasseursDApplis\Arrows\Test\Unit\Domain\VO;

use BrasseursDApplis\Arrows\VO\Orientation;
use BrasseursDApplis\Arrows\VO\Position;
use BrasseursDApplis\Arrows\VO\Sequence;

class SequenceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldBuildACompletelyNonCongruentSequence()
    {
        $position = Position::top();
        $previewOrientation = Orientation::right();
        $initiationOrientation = Orientation::right();
        $mainOrientation = Orientation::left();

        $sequence = new Sequence(
            $position,
            $previewOrientation,
            $initiationOrientation,
            $mainOrientation
        );

        $this->assertEquals($position, $sequence->getPosition());
        $this->assertEquals($previewOrientation, $sequence->getPreviewOrientation());
        $this->assertEquals($initiationOrientation, $sequence->getInitiationOrientation());
        $this->assertEquals($mainOrientation, $sequence->getMainOrientation());
        $this->assertFalse($sequence->isCongruentWithInitiation());
        $this->assertFalse($sequence->isCongruentWithPreview());
    }

    /**
     * @test
     */
    public function itShouldBuildANonCongruentInitiationSequence()
    {
        $position = Position::top();
        $previewOrientation = Orientation::left();
        $initiationOrientation = Orientation::right();
        $mainOrientation = Orientation::left();

        $sequence = new Sequence(
            $position,
            $previewOrientation,
            $initiationOrientation,
            $mainOrientation
        );

        $this->assertEquals($position, $sequence->getPosition());
        $this->assertEquals($previewOrientation, $sequence->getPreviewOrientation());
        $this->assertEquals($initiationOrientation, $sequence->getInitiationOrientation());
        $this->assertEquals($mainOrientation, $sequence->getMainOrientation());
        $this->assertFalse($sequence->isCongruentWithInitiation());
        $this->assertTrue($sequence->isCongruentWithPreview());
    }

    /**
     * @test
     */
    public function itShouldBuildANonCongruentPreviewSequence()
    {
        $position = Position::top();
        $previewOrientation = Orientation::right();
        $initiationOrientation = Orientation::left();
        $mainOrientation = Orientation::left();

        $sequence = new Sequence(
            $position,
            $previewOrientation,
            $initiationOrientation,
            $mainOrientation
        );

        $this->assertEquals($position, $sequence->getPosition());
        $this->assertEquals($previewOrientation, $sequence->getPreviewOrientation());
        $this->assertEquals($initiationOrientation, $sequence->getInitiationOrientation());
        $this->assertEquals($mainOrientation, $sequence->getMainOrientation());
        $this->assertTrue($sequence->isCongruentWithInitiation());
        $this->assertFalse($sequence->isCongruentWithPreview());
    }

    /**
     * @test
     */
    public function itShouldBuildACongruentSequence()
    {
        $position = Position::top();
        $previewOrientation = Orientation::right();
        $initiationOrientation = Orientation::right();
        $mainOrientation = Orientation::right();

        $sequence = new Sequence(
            $position,
            $previewOrientation,
            $initiationOrientation,
            $mainOrientation
        );

        $this->assertEquals($position, $sequence->getPosition());
        $this->assertEquals($previewOrientation, $sequence->getPreviewOrientation());
        $this->assertEquals($initiationOrientation, $sequence->getInitiationOrientation());
        $this->assertEquals($mainOrientation, $sequence->getMainOrientation());
        $this->assertTrue($sequence->isCongruentWithInitiation());
        $this->assertTrue($sequence->isCongruentWithPreview());
    }
}
