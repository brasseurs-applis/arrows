<?php

namespace BrasseursDApplis\Arrows\Test\Unit\Domain;

use BrasseursDApplis\Arrows\Exception\ScenarioException;
use BrasseursDApplis\Arrows\Scenario;
use BrasseursDApplis\Arrows\VO\Orientation as O;
use BrasseursDApplis\Arrows\VO\Position as P;
use BrasseursDApplis\Arrows\VO\Sequence;
use Faker\Factory;

class ScenarioTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $name;

    /** @var int */
    private $nbSequences;

    /** @var Sequence */
    private $firstSequence;

    /** @var Sequence */
    private $secondSequence;

    /** @var Sequence */
    private $thirdSequence;

    /** @var Sequence */
    private $fourthSequence;

    /** @var Scenario */
    private $serviceUnderTest;

    /**
     * Init
     */
    public function setUp()
    {
        $faker = Factory::create();

        $this->name = $faker->userName;
        $this->nbSequences = 3;

        $this->firstSequence = new Sequence(P::top(), O::left(), O::right(), O::left());
        $this->secondSequence = new Sequence(P::bottom(), O::left(), O::left(), O::left());
        $this->thirdSequence = new Sequence(P::top(), O::right(), O::right(), O::left());
        $this->fourthSequence = new Sequence(P::bottom(), O::left(), O::right(), O::right());

        $this->serviceUnderTest = new Scenario($this->name, $this->nbSequences);
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
    public function itShouldHaveAName()
    {
        $this->assertEquals($this->name, $this->serviceUnderTest->getName());
    }

    /**
     * @test
     */
    public function itShouldAllowToAddNoMoreThanTheSpecifiedNumberOfSequences()
    {
        $this->assertFalse($this->serviceUnderTest->isComplete());
        $this->serviceUnderTest->addSequence($this->firstSequence);

        $this->assertFalse($this->serviceUnderTest->isComplete());
        $this->serviceUnderTest->addSequence($this->secondSequence);

        $this->assertFalse($this->serviceUnderTest->isComplete());
        $this->serviceUnderTest->addSequence($this->thirdSequence);

        $this->assertTrue($this->serviceUnderTest->isComplete());

        $this->setExpectedException(ScenarioException::class);
        $this->serviceUnderTest->addSequence($this->fourthSequence);
    }

    /**
     * @test
     */
    public function itShouldAllowToReplaceASequence()
    {
        $this->serviceUnderTest->addSequence($this->firstSequence);
        $this->serviceUnderTest->addSequence($this->secondSequence);
        $this->serviceUnderTest->addSequence($this->thirdSequence);

        $this->serviceUnderTest->replaceSequence(0, $this->fourthSequence);
        $this->assertEquals($this->fourthSequence, $this->serviceUnderTest->run());
    }

    /**
     * @test
     */
    public function itShouldNotAllowToReplaceANonExistingSequence()
    {
        $this->setExpectedException(ScenarioException::class);

        $this->serviceUnderTest->replaceSequence(0, $this->fourthSequence);
    }

    /**
     * @test
     */
    public function itShouldAllowToRunScenarioIfComplete()
    {
        $this->serviceUnderTest->addSequence($this->firstSequence);
        $this->serviceUnderTest->addSequence($this->secondSequence);
        $this->serviceUnderTest->addSequence($this->thirdSequence);

        $this->assertEquals($this->firstSequence, $this->serviceUnderTest->run());
        $this->assertEquals($this->firstSequence, $this->serviceUnderTest->getCurrent());
        $this->assertEquals($this->secondSequence, $this->serviceUnderTest->getNext());
        $this->assertEquals($this->secondSequence, $this->serviceUnderTest->getCurrent());
        $this->assertEquals($this->thirdSequence, $this->serviceUnderTest->getNext());
        $this->assertEquals($this->thirdSequence, $this->serviceUnderTest->getCurrent());

        $this->setExpectedException(ScenarioException::class);
        $this->serviceUnderTest->getNext();
    }

    /**
     * @test
     */
    public function itShouldNotAllowToRunScenarioIfIncomplete()
    {
        $this->serviceUnderTest->addSequence($this->firstSequence);
        $this->serviceUnderTest->addSequence($this->secondSequence);

        $this->setExpectedException(ScenarioException::class);
        $this->serviceUnderTest->run();
    }

    /**
     * @test
     */
    public function itShouldNotBePossibleToGetTheCurrentSequenceOfANonRunningScenario()
    {
        $this->serviceUnderTest->addSequence($this->firstSequence);
        $this->serviceUnderTest->addSequence($this->secondSequence);
        $this->serviceUnderTest->addSequence($this->thirdSequence);

        $this->setExpectedException(ScenarioException::class);
        $this->serviceUnderTest->getCurrent();
    }

    /**
     * @test
     */
    public function itShouldNotAllowToRunScenarioMoreThanOnce()
    {
        $this->serviceUnderTest->addSequence($this->firstSequence);
        $this->serviceUnderTest->addSequence($this->secondSequence);
        $this->serviceUnderTest->addSequence($this->thirdSequence);
        $this->serviceUnderTest->run();

        $this->setExpectedException(ScenarioException::class);
        $this->serviceUnderTest->run();
    }

    /**
     * @test
     */
    public function itShouldBeAbleToStopTheScenario()
    {
        $this->serviceUnderTest->addSequence($this->firstSequence);
        $this->serviceUnderTest->addSequence($this->secondSequence);
        $this->serviceUnderTest->addSequence($this->thirdSequence);
        $this->serviceUnderTest->run();
        $this->serviceUnderTest->stop();

        $this->setExpectedException(ScenarioException::class);
        $this->serviceUnderTest->getCurrent();
    }
}
