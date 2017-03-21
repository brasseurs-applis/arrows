<?php

namespace BrasseursApplis\Arrows\App\DTO;

use BrasseursApplis\Arrows\VO\Scenario;
use Doctrine\Common\Collections\ArrayCollection;

class SessionDTO
{
    /** @var string */
    private $id;

    /** @var UserDTO */
    private $researcher;

    /** @var UserDTO */
    private $subjectOne;

    /** @var UserDTO */
    private $subjectTwo;

    /** @var ScenarioDTO */
    private $scenarioTemplate;

    /** @var Scenario */
    private $scenario;

    /** @var ResultDTO[] | ArrayCollection */
    private $results;

    /**
     * SessionDTO constructor.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->results = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return UserDTO
     */
    public function getResearcher()
    {
        return $this->researcher;
    }

    /**
     * @param UserDTO $researcher
     */
    public function setResearcher(UserDTO $researcher)
    {
        $this->researcher = $researcher;
    }

    /**
     * @return UserDTO
     */
    public function getSubjectOne()
    {
        return $this->subjectOne;
    }

    /**
     * @param UserDTO $subjectOne
     */
    public function setSubjectOne(UserDTO $subjectOne)
    {
        $this->subjectOne = $subjectOne;
    }

    /**
     * @return UserDTO
     */
    public function getSubjectTwo()
    {
        return $this->subjectTwo;
    }

    /**
     * @param UserDTO $subjectTwo
     */
    public function setSubjectTwo(UserDTO $subjectTwo)
    {
        $this->subjectTwo = $subjectTwo;
    }

    /**
     * @return ScenarioDTO
     */
    public function getScenarioTemplate()
    {
        return $this->scenarioTemplate;
    }

    /**
     * @param ScenarioDTO $scenarioTemplate
     */
    public function setScenarioTemplate(ScenarioDTO $scenarioTemplate)
    {
        $this->scenarioTemplate = $scenarioTemplate;
    }

    /**
     * @return Scenario
     */
    public function getScenario()
    {
        return $this->scenario;
    }

    /**
     * @param Scenario $scenario
     */
    public function setScenario(Scenario $scenario)
    {
        $this->scenario = $scenario;
    }

    /**
     * @return ResultDTO[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return int
     */
    public function numberOfSequences()
    {
        return $this->scenario->countSequences();
    }
}
