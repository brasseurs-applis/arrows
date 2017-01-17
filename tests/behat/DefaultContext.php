<?php

namespace BrasseursApplis\Arrows\Test\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

class DefaultContext implements Context
{
    /**
     * @Given I am an administrator
     */
    public function iAmAnAdministrator()
    {
        throw new PendingException();
    }

    /**
     * @Given I want the session to be random
     */
    public function iWantTheSessionToBeRandom()
    {
        throw new PendingException();
    }

    /**
     * @When I create the session
     */
    public function iCreateTheSession()
    {
        throw new PendingException();
    }

    /**
     * @Then the session should be available for tester on screen #1
     */
    public function theSessionShouldBeAvailableForTesterOnScreen()
    {
        throw new PendingException();
    }

    /**
     * @Given the session should be available for tester on screen #2
     */
    public function theSessionShouldBeAvailableForTesterOnScreen1()
    {
        throw new PendingException();
    }
}
