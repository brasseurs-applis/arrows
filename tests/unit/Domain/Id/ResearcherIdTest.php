<?php

namespace BrasseursApplis\Arrows\Test\Unit\Domain\Id;

use BrasseursApplis\Arrows\Id\ResearcherId;
use Faker\Factory;

class ResearcherIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldAcceptAnUuid()
    {
        $faker = Factory::create();

        $uuid = $faker->uuid;
        $id = new ResearcherId($uuid);

        $this->assertEquals($uuid, (string) $id);
    }

    /**
     * @test
     */
    public function itShouldNotAcceptOtherThanUuid()
    {
        $faker = Factory::create();

        $this->setExpectedException(\InvalidArgumentException::class);
        new ResearcherId($faker->randomNumber());
    }
}
