<?php

namespace BrasseursDApplis\Arrows\Test\Unit\Domain\Id;

use BrasseursDApplis\Arrows\Id\ScenarioId;
use Faker\Factory;

class ScenarioIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldAcceptAnUuid()
    {
        $faker = Factory::create();

        $uuid = $faker->uuid;
        $id = new ScenarioId($uuid);

        $this->assertEquals($uuid, (string) $id);
    }

    /**
     * @test
     */
    public function itShouldNotAcceptOtherThanUuid()
    {
        $faker = Factory::create();

        $this->setExpectedException(\InvalidArgumentException::class);
        new ScenarioId($faker->randomNumber());
    }
}
