<?php

namespace BrasseursApplis\Arrows;

use Assert\Assertion;
use BrasseursApplis\Arrows\Id\UserId;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class User
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_RESEARCHER = 'ROLE_RESEARCHER';
    const ROLE_SUBJECT = 'ROLE_SUBJECT';

    /** @var UserId */
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
     * User constructor.
     *
     * @param UserId   $id
     * @param string   $userName
     */
    public function __construct(UserId $id, $userName)
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->roles = [];
        $this->salt = base64_encode(random_bytes(10));
    }

    /**
     * @param string                   $password
     * @param PasswordEncoderInterface $passwordEncoder
     */
    public function changePassword($password, PasswordEncoderInterface $passwordEncoder)
    {
        $this->password = $passwordEncoder->encodePassword($password, $this->salt);
    }

    /**
     * @param string[] $roles
     */
    public function updateRoles(array $roles)
    {
        $removeRoles = array_diff($this->roles, $roles);
        foreach ($removeRoles as $role) {
            $this->removeRole($role);
        }

        $addRoles = array_diff($roles, $this->roles);
        foreach ($addRoles as $role) {
            $this->addRole($role);
        }

        $this->keepRolesClean();
    }

    /**
     * @param string $role
     */
    public function addRole($role)
    {
        Assertion::inArray($role, [ self::ROLE_ADMIN, self::ROLE_SUBJECT, self::ROLE_RESEARCHER ]);

        if (in_array($role, $this->roles)) {
            return;
        }

        $this->roles[] = $role;

        $this->keepRolesClean();
    }

    /**
     * @param string $role
     */
    public function removeRole($role)
    {
        Assertion::inArray($role, [ self::ROLE_ADMIN, self::ROLE_SUBJECT, self::ROLE_RESEARCHER ]);

        $position = array_search($role, $this->roles);

        if ($position === null) {
            return;
        }

        unset($this->roles[$position]);

        $this->keepRolesClean();
    }

    /**
     * @return UserId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        return array_values($this->roles);
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     *
     */
    private function keepRolesClean()
    {
        $this->roles = array_values($this->roles);
    }
}
