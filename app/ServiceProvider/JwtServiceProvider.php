<?php

namespace BrasseursApplis\Arrows\App\ServiceProvider;

use BrasseursApplis\Arrows\App\ServiceProvider\JWT\GuzzleRequestRetrievalStrategy;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class JwtServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app
     */
    public function register(Container $app)
    {
        $app['security.jwt_retrieval.strategy'] = function () use ($app) {
            return new GuzzleRequestRetrievalStrategy();
        };
        
        $app['security.entry_point.jwt._proto'] = $app->protect(
            function () use ($app) {
                return function () {
                    return new JwtAuthenticationEntryPoint();
                };
            }
        );

        $app['security.jwt.authenticator'] = function () use ($app) {
            return new JwtAuthenticator(
                $app['security.token_storage'],
                $app['security.authentication_manager'],
                $app['security.jwt_retrieval.strategy']
            );
        };
        
        $app['security.authentication_listener.factory.jwt'] = $app->protect(
            function ($name, $options) use ($app) {
                $app['security.authentication_provider.' . $name . '.jwt'] = function () use ($app, $options) {
                    $userBuilder = new JwtUserBuilder($options['secret_key'], $options['allowed_algorithms']);
                    return new JwtAuthenticationProvider($userBuilder);
                };
                
                $app['security.authentication_listener.' . $name . '.jwt'] = function () use ($app, $name, $options) {
                    return new JwtListener($app['security.jwt.authenticator']);
                };
                
                $app['security.entry_point.' . $name . '.jwt'] = $app['security.entry_point.jwt._proto'](
                    $name,
                    $options
                );
                
                return array(
                    'security.authentication_provider.' . $name . '.jwt',
                    'security.authentication_listener.' . $name . '.jwt',
                    'security.entry_point.' . $name . '.jwt',
                    'pre_auth',
                );
            }
        );
    }
}
