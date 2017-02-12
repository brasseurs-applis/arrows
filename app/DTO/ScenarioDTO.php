<?php

namespace BrasseursApplis\Arrows\App\DTO;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class ScenarioDTO
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var SequenceDTO[] */
    private $sequences;

    /**
     * ScenarioDTO constructor.
     *
     * @param string        $id
     * @param string        $name
     * @param SequenceDTO[] $sequences
     */
    public function __construct(
        $id,
        $name = null,
        $sequences = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->sequences;
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return SequenceDTO[]
     */
    public function getSequences()
    {
        return $this->sequences;
    }

    /**
     * @param SequenceDTO[] $sequences
     */
    public function setSequences($sequences)
    {
        $this->sequences = $sequences;
    }


    /**
     * @param ClassMetadata $metadata
     *
     * @throws ConstraintDefinitionException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata
            ->addPropertyConstraint('name', new NotBlank());
    }
}
