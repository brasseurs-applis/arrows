<?php

namespace BrasseursApplis\Arrows\App\Security;

use BrasseursApplis\Arrows\App\DTO\UserDTO;
use BrasseursApplis\Arrows\Id\UserId;
use Firebase\JWT\JWT;
use RemiSan\Silex\JWT\Security\JwtUserBuilder;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

class ArrowsJwtUserBuilder implements JwtUserBuilder
{
    /** @var string */
    private $jwtKey;

    /** @var string[] */
    private $allowedAlgorithms;

    /**
     * @param string $jwtKey
     *
     * @return void
     */
    public function setJwtKey($jwtKey)
    {
        $this->jwtKey = $jwtKey;
    }

    /**
     * @param string[] $allowedAlgorithms
     *
     * @return void
     */
    public function setAllowedAlgorithms(array $allowedAlgorithms)
    {
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
            new UserDTO(
                new UserId($decodedJwt->sub),
                $decodedJwt->username,
                null,
                null,
                $decodedJwt->roles
            ),
            $this->jwtKey
        );
    }
}
