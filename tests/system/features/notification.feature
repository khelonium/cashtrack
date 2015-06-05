Feature: Notifications for overspending


  Scenario: Weekly Notifications
    Given there are some transactions which exceed the weekly limit
    When the monthly job runs
    Then the montly notification is triggered