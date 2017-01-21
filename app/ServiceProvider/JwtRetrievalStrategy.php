<?php

namespace BrasseursApplis\Arrows\App\ServiceProvider;

use BrasseursApplis\Arrows\App\ServiceProvider\JWT\Exception\JwtNotFoundException;

interface JwtRetrievalStrategy
{
    /**
     * @param mixed $request
     *
     * @return JwtToken
     *
     * @throws JwtNotFoundException
     */
    public function getToken($request);

    /**
     * @param mixed $request
     *
     * @return bool
     */
    public function supports($request);
}