<?php

namespace BrasseursApplis\Arrows\App\ServiceProvider;

use BrasseursApplis\Arrows\App\Security\AuthorizationUser;
use BrasseursApplis\Arrows\Id\UserId;
use BrasseursApplis\Arrows\User;
use Firebase\JWT\JWT;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

class JwtUserBuilder
{
    /** @var string */
    private $jwtKey;

    /** @var string[] */
    private $allowedAlgorithms;

    /**
     * JwtUserBuilder constructor.
     *
     * @param string   $jwtKey
     * @param string[] $allowedAlgorithms
     */
    public function __construct($jwtKey, array $allowedAlgorithms)
    {
        $this->jwtKey = $jwtKey;
        $this->allowedAlgorithms = $allowedAlgorithms;
    }

    /**
     * @param string $jwtString
     *
     * @return AdvancedUserInterface
     */
    public function buildUserFromToken($jwtString)
    {
        $decodedJwt = JWT::decode($jwtString, $this->jwtKey, $this->allowedAlgorithms);

        return new AuthorizationUser(
            new User(
                new UserId($decodedJwt->sub),
                $decodedJwt->username,
                '',
                '',
                $decodedJwt->roles
            ),
            $this->jwtKey
        );
    }
}
