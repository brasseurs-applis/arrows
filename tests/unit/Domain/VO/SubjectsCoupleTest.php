<?php

namespace BrasseursApplis\Arrows\Test\Unit\Domain\VO;

use BrasseursApplis\Arrows\Id\SubjectId;
use BrasseursApplis\Arrows\VO\SubjectsCouple;
use Faker\Factory;

class SubjectsCoupleTest extends \PHPUnit_Framework_TestCase
{
    /** @var SubjectId */
    private $positionOne;

    /** @var SubjectId */
    private $positionTwo;

    /** @var SubjectsCouple */
    private $serviceUnderTest;

    /**
     * Init
     */
    public function setUp()
    {
        $faker = Factory::create();

        $this->positionOne = new SubjectId($faker->uuid);
        $this->positionTwo = new SubjectId($faker->uuid);

        $this->serviceUnderTest = new SubjectsCouple($this->positionOne, $this->positionTwo);
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
    public function itShouldHaveASubjectOnPositionOne()
    {
        $this->assertEquals($this->positionOne, $this->serviceUnderTest->getPositionOne());
    }

    /**
     * @test
     */
    public function itShouldHaveASubjectOnPositionTwo()
    {
        $this->assertEquals($this->positionTwo, $this->serviceUnderTest->getPositionTwo());
    }
}
