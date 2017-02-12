<?php

namespace BrasseursApplis\Arrows\App\DTO;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class SequenceDTO
{
    /** @var string */
    private $id;

    /** @var string */
    private $position;

    /** @var string */
    private $previewOrientation;

    /** @var string */
    private $initiationOrientation;

    /** @var string */
    private $mainOrientation;

    /**
     * ScenarioDTO constructor.
     *
     * @param string $id
     * @param string $position
     * @param string $previewOrientation
     * @param string $initiationOrientation
     * @param string $mainOrientation
     */
    public function __construct(
        $id = null,
        $position = null,
        $previewOrientation = null,
        $initiationOrientation = null,
        $mainOrientation = null
    ) {
        $this->id = ($id === null) ? (string) Uuid::uuid4() : $id;
        $this->position = $position;
        $this->previewOrientation = $previewOrientation;
        $this->initiationOrientation = $initiationOrientation;
        $this->mainOrientation = $mainOrientation;
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
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getPreviewOrientation()
    {
        return $this->previewOrientation;
    }

    /**
     * @param string $previewOrientation
     */
    public function setPreviewOrientation($previewOrientation)
    {
        $this->previewOrientation = $previewOrientation;
    }

    /**
     * @return string
     */
    public function getInitiationOrientation()
    {
        return $this->initiationOrientation;
    }

    /**
     * @param string $initiationOrientation
     */
    public function setInitiationOrientation($initiationOrientation)
    {
        $this->initiationOrientation = $initiationOrientation;
    }

    /**
     * @return string
     */
    public function getMainOrientation()
    {
        return $this->mainOrientation;
    }

    /**
     * @param string $mainOrientation
     */
    public function setMainOrientation($mainOrientation)
    {
        $this->mainOrientation = $mainOrientation;
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
    }
}
