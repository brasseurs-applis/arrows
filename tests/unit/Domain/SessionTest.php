<?php

namespace BrasseursDApplis\Arrows\Test\Unit\Domain;

use BrasseursDApplis\Arrows\Id\SessionId;
use BrasseursDApplis\Arrows\Scenario;
use BrasseursDApplis\Arrows\Session;
use BrasseursDApplis\Arrows\VO\Duration;
use BrasseursDApplis\Arrows\VO\Orientation;
use BrasseursDApplis\Arrows\VO\Sequence;
use Faker\Factory;
use Mockery\Mock;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    /** @var SessionId */
    private $sessionId;

    /** @var Scenario | Mock */
    private $scenario;

    /** @var Sequence */
    private $sequence;

    /** @var Orientation */
    private $orientation;

    /** @var Duration */
    private $duration;

    /** @var Session */
    private $serviceUnderTest;

    /**
     * Init
     */
    public function setUp()
    {
        $faker = Factory::create();

        $this->sessionId = new SessionId($faker->uuid);
        $this->scenario = \Mockery::mock(Scenario::class);
        $this->sequence = \Mockery::mock(Sequence::class);
        $this->orientation = Orientation::left();
        $this->duration = \Mockery::mock(Duration::class);
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
    public function itShouldFailBuildingWithAnIncompleteScenario()
    {
        $this->givenScenarioIsNotComplete();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->givenASession();
    }

    /**
     * @test
     */
    public function itShouldHaveAnId()
    {
        $this->givenScenarioIsComplete();
        $this->givenASession();

        $this->assertEquals($this->sessionId, $this->serviceUnderTest->getId());
    }

    /**
     * @test
     */
    public function itShouldFollowAScenario()
    {
        $this->givenScenarioIsComplete();
        $this->givenASession();

        $this->assertEquals($this->scenario, $this->serviceUnderTest->getScenario());
    }

    /**
     * @test
     */
    public function itShouldRunTheFirstSequenceOfTheScenario()
    {
        $this->givenScenarioIsComplete();
        $this->givenScenarioCanRun();
        $this->givenASession();

        $sequence = $this->serviceUnderTest->start();
        $this->assertEquals($this->sequence, $sequence);
    }

    /**
     * @test
     */
    public function itShouldNotBeAbleToStartTheSessionMoreThanOnce()
    {
        $this->givenScenarioIsComplete();
        $this->givenScenarioCanRun();
        $this->givenASession();

        $this->serviceUnderTest->start();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->start();
    }

    /**
     * @test
     */
    public function itShouldNotBePossibleToAddAResultIfThereIsNoCurrentSequence()
    {
        $this->givenScenarioIsComplete();
        $this->givenScenarioCanRun();
        $this->givenASession();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->result($this->orientation, $this->duration);
    }

    /**
     * @test
     */
    public function itShouldAcceptAResult()
    {
        $this->givenScenarioIsComplete();
        $this->givenScenarioCanRun();
        $this->givenASession();

        $sequence = $this->serviceUnderTest->start();
        $result = $this->serviceUnderTest->result($this->orientation, $this->duration);

        $this->assertEquals($sequence, $result->getSequence());
        $this->assertEquals($this->orientation, $result->getOrientation());
        $this->assertEquals($this->duration, $result->getDuration());
    }

    /**
     * @test
     */
    public function itShouldNotBePossibleToAddASecondResultForTheSameSequence()
    {
        $this->givenScenarioIsComplete();
        $this->givenScenarioCanRun();
        $this->givenASession();

        $this->serviceUnderTest->start();
        $this->serviceUnderTest->result($this->orientation, $this->duration);

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->result($this->orientation, $this->duration);
    }

    private function givenASession()
    {
        $this->serviceUnderTest = new Session($this->sessionId, $this->scenario);
    }

    private function givenScenarioIsNotComplete()
    {
        $this->scenario->shouldReceive('isComplete')->andReturn(false);
    }

    private function givenScenarioIsComplete()
    {
        $this->scenario->shouldReceive('isComplete')->andReturn(true);
    }

    private function givenScenarioCanRun()
    {
        $this->scenario->shouldReceive('run')->andReturn($this->sequence);
    }
}
