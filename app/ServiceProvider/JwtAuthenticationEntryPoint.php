<?php

namespace BrasseursApplis\Arrows\App\ServiceProvider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class JwtAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new Response('', 401);
    }
}
