Feature: Creating a session and connecting to it
  In order to run the experiment
  As an administrator
  I want to create sessions and allow testers to connect to it

  Scenario: A geonames entity creation will result in a Ptolemaeus place creation if it does not exist yet
    Given I am an administrator
    And I want the session to be random
    When I create the session
    Then the session should be available for tester on screen #1
    And the session should be available for tester on screen #2
