Feature: As a developer
  I want to be able to configure how the laucnhdarkly user is built
  So that I can use the correct details

  Scenario: The user id is provided by a custom user id provider
    Given I fix the user id to "12345"
    When I visit the homepage
    Then "12345" should have been used to identify me