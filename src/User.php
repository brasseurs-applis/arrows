<?php

namespace BrasseursApplis\Arrows;

use Assert\Assertion;
use BrasseursApplis\Arrows\Id\UserId;

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
     * @param string   $password
     * @param string   $salt
     * @param string[] $roles
     */
    public function __construct(UserId $id, $userName, $password, $salt, array $roles = [])
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->password = $password;
        $this->roles = $roles;
        $this->salt = $salt;
    }

    /**
     * @param string $password
     */
    public function changePassword($password)
    {
        $this->password = $password;
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
        return $this->roles;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }
}
