Feature: Expenses can be breakdown by category


  Scenario: Expenses can be grouped by category for a given week
    Given there are some transactions
    When when I check the week breakdown api for that given week
    Then the expenses are grouped by category and week

  Scenario: Expenses can be grouped by category for a given month
    Given there are some transactions
    When I check the month breakdown api for that given month
    Then the expenses are grouped by category and month

  Scenario: Expenses can be grouped by category for a given year
    Given there are some transactions
    When I check the month breakdown api for that given year
    Then the expenses are grouped by category and year