<?php

namespace BrasseursApplis\Arrows\Test\Unit\Domain\VO;

use BrasseursApplis\Arrows\Exception\ScenarioException;
use BrasseursApplis\Arrows\VO\Orientation as O;
use BrasseursApplis\Arrows\VO\Position as P;
use BrasseursApplis\Arrows\VO\Scenario;
use BrasseursApplis\Arrows\VO\Sequence;
use BrasseursApplis\Arrows\VO\SequenceCollection;
use Mockery\Mock;

class ScenarioTest extends \PHPUnit_Framework_TestCase
{
    /** @var SequenceCollection | Mock */
    private $sequences;

    /** @var Sequence */
    private $firstSequence;

    /** @var Sequence */
    private $secondSequence;

    /** @var Sequence */
    private $thirdSequence;

    /** @var \BrasseursApplis\Arrows\VO\Scenario */
    private $serviceUnderTest;

    /**
     * Init
     */
    public function setUp()
    {
        $this->firstSequence = new Sequence(P::top(), O::left(), O::right(), O::left());
        $this->secondSequence = new Sequence(P::bottom(), O::left(), O::left(), O::left());
        $this->thirdSequence = new Sequence(P::top(), O::right(), O::right(), O::left());
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
    public function itShouldAllowToRunScenarioIfComplete()
    {
        $this->givenACompleteScenario();
        $this->givenARunningScenario();

        $this->assertEquals($this->firstSequence, $this->serviceUnderTest->run());
        $this->assertEquals($this->firstSequence, $this->serviceUnderTest->current());
        $this->assertEquals($this->secondSequence, $this->serviceUnderTest->next());
        $this->assertEquals($this->secondSequence, $this->serviceUnderTest->current());
        $this->assertEquals($this->thirdSequence, $this->serviceUnderTest->next());
        $this->assertEquals($this->thirdSequence, $this->serviceUnderTest->current());

        $this->setExpectedException(ScenarioException::class);
        $this->serviceUnderTest->next();
    }

    /**
     * @test
     */
    public function itShouldNotBePossibleToGetTheCurrentSequenceOfANonRunningScenario()
    {
        $this->givenACompleteScenario();
        $this->givenARunningScenario();

        $this->assertFalse($this->serviceUnderTest->isRunning());

        $this->setExpectedException(ScenarioException::class);
        $this->serviceUnderTest->current();
    }

    /**
     * @test
     */
    public function itShouldNotAllowToRunScenarioMoreThanOnce()
    {
        $this->givenACompleteScenario();
        $this->givenARunningScenario();

        $this->serviceUnderTest->run();
        $this->assertTrue($this->serviceUnderTest->isRunning());

        $this->setExpectedException(ScenarioException::class);
        $this->serviceUnderTest->run();
    }

    /**
     * @test
     */
    public function itShouldBeAbleToStopTheScenario()
    {
        $this->givenACompleteScenario();
        $this->givenARunningScenario();

        $this->serviceUnderTest->run();
        $this->assertTrue($this->serviceUnderTest->isRunning());
        $this->serviceUnderTest->stop();
        $this->assertFalse($this->serviceUnderTest->isRunning());

        $this->setExpectedException(ScenarioException::class);
        $this->serviceUnderTest->current();
    }

    private function givenACompleteScenario()
    {
        $this->sequences = new SequenceCollection([$this->firstSequence, $this->secondSequence, $this->thirdSequence]);
    }

    protected function givenARunningScenario()
    {
        $this->serviceUnderTest = new Scenario($this->sequences);
    }
}
