<?php

namespace BrasseursApplis\Arrows\App\DTO;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class UserDTO
{
    /** @var string */
    private $id;

    /** @var string */
    private $userName;

    /** @var string */
    private $password;

    /** @var string */
    private $salt;

    /** @var string[] */
    private $roles;

    /**
     * UserDTO constructor.
     *
     * @param string   $id
     * @param string   $userName
     * @param string   $password
     * @param null     $salt
     * @param string[] $roles
     */
    public function __construct(
        $id,
        $userName = null,
        $password = null,
        $salt = null,
        array $roles = []
    ) {
        $this->id = $id;
        $this->userName = $userName;
        $this->password = $password;
        $this->roles = $roles;
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
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
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
            ->addPropertyConstraint('userName', new NotBlank())
            ->addPropertyConstraint('password', new NotBlank())
            ->addPropertyConstraint('password', new Length(['min' => 5]))
            ->addPropertyConstraint('roles', new NotBlank());
    }
}
