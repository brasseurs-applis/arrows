<?php

namespace BrasseursApplis\Arrows\App\DTO;

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
    public function setResearcher($researcher)
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
    public function setSubjectOne($subjectOne)
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
    public function setSubjectTwo($subjectTwo)
    {
        $this->subjectTwo = $subjectTwo;
    }

    /**
     * @return ScenarioDTO
     */
    public function getScenario()
    {
        return $this->scenario;
    }

    /**
     * @param ScenarioDTO $scenario
     */
    public function setScenario($scenario)
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
        return count($this->scenario->getSequences());
    }
}
