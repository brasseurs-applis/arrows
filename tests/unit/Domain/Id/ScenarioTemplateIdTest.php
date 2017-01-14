<?php

namespace BrasseursDApplis\Arrows\Test\Unit\Domain\Id;

use BrasseursDApplis\Arrows\Id\ScenarioTemplateId;
use Faker\Factory;

class ScenarioTemplateIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldAcceptAnUuid()
    {
        $faker = Factory::create();

        $uuid = $faker->uuid;
        $id = new ScenarioTemplateId($uuid);

        $this->assertEquals($uuid, (string) $id);
    }

    /**
     * @test
     */
    public function itShouldNotAcceptOtherThanUuid()
    {
        $faker = Factory::create();

        $this->setExpectedException(\InvalidArgumentException::class);
        new ScenarioTemplateId($faker->randomNumber());
    }
}
