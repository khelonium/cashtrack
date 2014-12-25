Feature: cashtrack   api is up
  In order to present the financial data
  As a web app
  There is a need to have a REST API

  Scenario: Balance API Does not have errors
    When I am on "/api/balance"
    Then the response status code should be 200
    And I should see a json response


  Scenario: Cashflow API Does not have errors
    When I am on "/api/cashflow"
    Then the response status code should be 200
    And I should see a json response


  Scenario: Merchant API Does not have errors
    When I am on "/api/merchant"
    Then the response status code should be 200
    And I should see a json response


  Scenario: Account API Does not have errors
    When I am on "/api/merchant"
    Then the response status code should be 200
    And I should see a json response


  Scenario: Account API Does not have errors
    When I am on "/api/transaction"
    Then the response status code should be 200
    And I should see a json response

