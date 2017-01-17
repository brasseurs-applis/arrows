<?php

namespace BrasseursApplis\Arrows\Test\Unit\Domain\Id;

use BrasseursApplis\Arrows\Id\SessionId;
use Faker\Factory;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldAcceptAnUuid()
    {
        $faker = Factory::create();

        $uuid = $faker->uuid;
        $id = new SessionId($uuid);

        $this->assertEquals($uuid, (string) $id);
    }

    /**
     * @test
     */
    public function itShouldNotAcceptOtherThanUuid()
    {
        $faker = Factory::create();

        $this->setExpectedException(\InvalidArgumentException::class);
        new SessionId($faker->randomNumber());
    }
}
