<?php

namespace BrasseursApplis\Arrows\Test\Unit\Domain;

use BrasseursApplis\Arrows\Id\ResearcherId;
use BrasseursApplis\Arrows\Id\ScenarioTemplateId;
use BrasseursApplis\Arrows\Id\SessionId;
use BrasseursApplis\Arrows\Id\SubjectId;
use BrasseursApplis\Arrows\Result;
use BrasseursApplis\Arrows\Session;
use BrasseursApplis\Arrows\VO\Duration;
use BrasseursApplis\Arrows\VO\Orientation;
use BrasseursApplis\Arrows\VO\Scenario;
use BrasseursApplis\Arrows\VO\Sequence;
use BrasseursApplis\Arrows\VO\SubjectsCouple;
use Faker\Factory;
use Mockery\Mock;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    /** @var SessionId */
    private $sessionId;

    /** @var ScenarioTemplateId */
    private $scenarioTemplateId;

    /** @var Scenario | Mock */
    private $scenario;

    /** @var SubjectsCouple */
    private $subjects;

    /** @var ResearcherId */
    private $observer;

    /** @var Sequence */
    private $sequence;

    /** @var Sequence */
    private $nextSequence;

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
        $this->scenarioTemplateId = new ScenarioTemplateId($faker->uuid);
        $this->scenario = \Mockery::mock(Scenario::class);
        $this->subjects = new SubjectsCouple(new SubjectId($faker->uuid), new SubjectId($faker->uuid));
        $this->observer = new ResearcherId($faker->uuid);

        $this->sequence = \Mockery::mock(Sequence::class);
        $this->nextSequence = \Mockery::mock(Sequence::class);
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
    public function itShouldHaveAnId()
    {
        $this->givenASession();

        $this->assertEquals($this->sessionId, $this->serviceUnderTest->getId());
    }

    /**
     * @test
     */
    public function itShouldFollowAScenario()
    {
        $this->givenASession();

        $this->assertEquals($this->scenario, $this->serviceUnderTest->getScenario());
    }

    /**
     * @test
     */
    public function itShouldHaveTestSubjects()
    {
        $this->givenASession();

        $this->assertEquals($this->subjects, $this->serviceUnderTest->getSubjects());
    }

    /**
     * @test
     */
    public function itShouldHaveAnObserver()
    {
        $this->givenASession();

        $this->assertEquals($this->observer, $this->serviceUnderTest->getObserver());
    }

    /**
     * @test
     */
    public function itShouldRunTheFirstSequenceOfTheScenario()
    {
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
        $this->givenThereIsNoCurrentSequence();
        $this->givenASession();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->result($this->orientation, $this->duration);
    }

    /**
     * @test
     */
    public function itShouldAcceptAResult()
    {
        $this->givenScenarioCanRun();
        $this->givenScenarioHasANextSequence();
        $this->givenASession();

        $sequence = $this->serviceUnderTest->start();
        $nextSequence = $this->serviceUnderTest->result($this->orientation, $this->duration);

        $this->assertEquals($this->sequence, $sequence);
        $this->assertEquals($this->nextSequence, $nextSequence);

        $results = $this->serviceUnderTest->getResults();
        /** @var Result $result */
        $result = $results[0];

        $this->assertEquals($sequence, $result->getSequence());
        $this->assertEquals($this->orientation, $result->getOrientation());
        $this->assertEquals($this->duration, $result->getDuration());
    }

    /**
     * @test
     */
    public function itShouldAcceptALastResult()
    {
        $this->givenScenarioCanRun();
        $this->givenScenarioHasNotANextSequence();
        $this->givenASession();
        $this->assertScenarioWillBeStoped();

        $sequence = $this->serviceUnderTest->start();
        $nextSequence = $this->serviceUnderTest->result($this->orientation, $this->duration);

        $this->assertEquals($this->sequence, $sequence);
        $this->assertNull($nextSequence);

        /** @var Result $result */
        $result = $this->serviceUnderTest->getResults()[0];

        $this->assertEquals($sequence, $result->getSequence());
        $this->assertEquals($this->orientation, $result->getOrientation());
        $this->assertEquals($this->duration, $result->getDuration());
    }

    private function givenASession()
    {
        $this->serviceUnderTest = new Session(
            $this->sessionId,
            $this->scenarioTemplateId,
            $this->scenario,
            $this->subjects,
            $this->observer
        );
    }

    private function givenScenarioCanRun()
    {
        $this->scenario->shouldReceive('run')->andReturn($this->sequence);
        $this->scenario->shouldReceive('current')->andReturn($this->sequence);
        $this->scenario->shouldReceive('isRunning')->andReturn(false, true);
    }

    private function givenThereIsNoCurrentSequence()
    {
        $this->scenario->shouldReceive('isRunning')->andReturn(false);
    }

    private function givenScenarioHasANextSequence()
    {
        $this->scenario->shouldReceive('hasNext')->andReturn(true);
        $this->scenario->shouldReceive('next')->andReturn($this->nextSequence);
    }

    private function givenScenarioHasNotANextSequence()
    {
        $this->scenario->shouldReceive('hasNext')->andReturn(false);
    }

    private function assertScenarioWillBeStoped()
    {
        $this->scenario->shouldReceive('stop')->once();
    }
}
