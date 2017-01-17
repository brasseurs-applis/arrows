<?php

namespace BrasseursApplis\Arrows\App\Security;

use BrasseursApplis\Arrows\User;
use Firebase\JWT\JWT;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

class AuthorizationUser implements AdvancedUserInterface
{
    /** @var User */
    private $user;

    /** @var string */
    private $jwt;

    /**
     * User constructor.
     *
     * @param User   $user
     * @param string $jwtKey
     */
    public function __construct(User $user, $jwtKey)
    {
        $this->user = $user;
        $this->jwt = JWT::encode([ 'sub' => (string) $user->getId() ], $jwtKey);
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->user->getUsername();
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->user->getPassword();
    }

    /**
     * @return null|string
     */
    public function getSalt()
    {
        return $this->user->getSalt();
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->user->getRoles();
    }

    /**
     * @void
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getJwt()
    {
        return $this->jwt;
    }
}
