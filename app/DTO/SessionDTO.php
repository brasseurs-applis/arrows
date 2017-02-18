<?php

namespace BrasseursApplis\Arrows\App\DTO;

use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use Symfony\Component\Validator\Mapping\ClassMetadata;

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

    /**
     * SessionDTO constructor.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
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
}
