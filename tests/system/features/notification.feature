Feature: Notifications for overspending


  Scenario: Monthly Notification with overflow
    Given there are some transactions which exceed the monthly limit
    When the monthly job runs
    Then the monthly notification is triggered


  Scenario: No Notification if no overflow
    Given there are some transactions which don't exceed monthly limit
    When the monthly job runs
    Then the monthly notification is not triggered

    Scenario: There is no notification if we don't almost exceed
      Given there are some transactions which do not exceed the warning limit
      When the monthly job runs
      Then the monthly notification is not triggered
      And  the monthly warning notification is not triggered

    Scenario: There is a warning before reaching the limit
      Given there are some transactions which exceed the warning threshold
      When the monthly job runs
      Then the monthly warning notification is triggered


  Scenario: Weekly Notification with overflow
    Given there are some transactions which exceed the weekly limit
    When the weekly job runs
    Then the weekly notification is triggered

  Scenario: There is a warning before we reach weekly limit
    Given there are some transactions which do not exceed the weekly warning limit
    When the weekly job runs
    Then the weekly notification is not triggered