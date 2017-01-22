<?php

namespace BrasseursApplis\Arrows\App\Form;

use BrasseursApplis\Arrows\User;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class UserForm
{
    /** @var string */
    private $id;

    /** @var string */
    private $userName;

    /** @var string */
    private $password;

    /** @var string[] */
    private $roles;

    /**
     * UserForm constructor.
     *
     * @param string   $id
     * @param string   $userName
     * @param string   $password
     * @param string[] $roles
     */
    public function __construct(
        $id,
        $userName = null,
        $password = null,
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
     * @return string[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @param ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('userName', new NotBlank());
        $metadata->addPropertyConstraint('password', new NotBlank());
        $metadata->addPropertyConstraint('password', new Length(['min' => 5]));
        $metadata->addPropertyConstraint('roles', new NotBlank());
    }

    /**
     * @param User $user
     *
     * @return UserForm
     */
    public static function fromEntity($user)
    {
        return new self(
            (string) $user->getId(),
            $user->getUserName(),
            null,
            $user->getRoles()
        );
    }
}
