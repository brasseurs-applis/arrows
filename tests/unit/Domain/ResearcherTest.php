<?php

namespace BrasseursApplis\Arrows\Test\Unit\Domain;

use BrasseursApplis\Arrows\Id\ResearcherId;
use BrasseursApplis\Arrows\Researcher;
use Faker\Factory;

class ResearcherTest extends \PHPUnit_Framework_TestCase
{
    /** @var ResearcherId */
    private $id;

    /** @var Researcher */
    private $serviceUnderTest;

    /**
     * Init
     */
    public function setUp()
    {
        $faker = Factory::create();

        $this->id = new ResearcherId($faker->uuid);

        $this->serviceUnderTest = new Researcher($this->id);
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
