<?php

namespace BrasseursApplis\Arrows\Exception;

use Assert\Assertion;

class ScenarioAssertion extends Assertion
{
    /**
     * Helper method that handles building the assertion failure exceptions.
     * They are returned from this method so that the stack trace still shows
     * the assertions method.
     *
     * @param mixed           $value
     * @param string|callable $message
     * @param int             $code
     * @param string|null     $propertyPath
     * @param array           $constraints
     *
     * @return mixed
     */
    protected static function createException($value, $message, $code, $propertyPath = null, array $constraints = [])
    {
        return new ScenarioException($message, $code);
    }
}
