<?php

namespace BrasseursApplis\Arrows\App\ServiceProvider\JWT;

use Assert\Assertion;
use BrasseursApplis\Arrows\App\ServiceProvider\JWT\Exception\JwtNotFoundException;
use BrasseursApplis\Arrows\App\ServiceProvider\JwtRetrievalStrategy;
use BrasseursApplis\Arrows\App\ServiceProvider\JwtToken;
use Guzzle\Http\Message\RequestInterface;

class GuzzleRequestRetrievalStrategy implements JwtRetrievalStrategy
{
    const HTTP_PARAM_JWT = 'jwt';

    /**
     * @param RequestInterface $request
     *
     * @return JwtToken
     *
     * @throws JwtNotFoundException
     */
    public function getToken($request)
    {
        Assertion::isInstanceOf($request, RequestInterface::class);

        $jwtString = $request->getQuery()->get(self::HTTP_PARAM_JWT);

        if ($jwtString === null) {
            throw new JwtNotFoundException();
        }

        $jwtToken = new JwtToken();
        $jwtToken->setToken($jwtString);

        return $jwtToken;
    }

    /**
     * @param mixed $request
     *
     * @return bool
     */
    public function supports($request)
    {
        return $request instanceof RequestInterface;
    }
}
