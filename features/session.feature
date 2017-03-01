Feature: Creating a session and connecting to it
  In order to run the experiment
  As an administrator
  I want to create sessions and allow testers to connect to it

  Scenario: A session can be created by an administrator
    Given I am an administrator
    And I want the session to run a random scenario
    When I create the session
    Then the session should be available for tester on screen #1
    And the session should be available for tester on screen #2
