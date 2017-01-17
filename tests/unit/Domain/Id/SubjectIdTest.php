<?php

namespace BrasseursApplis\Arrows\Test\Unit\Domain\Id;

use BrasseursApplis\Arrows\Id\SubjectId;
use Faker\Factory;

class SubjectIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldAcceptAnUuid()
    {
        $faker = Factory::create();

        $uuid = $faker->uuid;
        $id = new SubjectId($uuid);

        $this->assertEquals($uuid, (string) $id);
    }

    /**
     * @test
     */
    public function itShouldNotAcceptOtherThanUuid()
    {
        $faker = Factory::create();

        $this->setExpectedException(\InvalidArgumentException::class);
        new SubjectId($faker->randomNumber());
    }
}
