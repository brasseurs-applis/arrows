<?php

namespace BrasseursDApplis\Arrows\Test\Unit\Domain;

use BrasseursDApplis\Arrows\Id\SubjectId;
use BrasseursDApplis\Arrows\Subject;
use Faker\Factory;

class SubjectTest extends \PHPUnit_Framework_TestCase
{
    /** @var SubjectId */
    private $id;

    /** @var Subject */
    private $serviceUnderTest;

    /**
     * Init
     */
    public function setUp()
    {
        $faker = Factory::create();

        $this->id = new SubjectId($faker->uuid);

        $this->serviceUnderTest = new Subject($this->id);
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
    public function itShouldHavAnId()
    {
        $this->assertEquals($this->id, $this->serviceUnderTest->getId());
    }
}
