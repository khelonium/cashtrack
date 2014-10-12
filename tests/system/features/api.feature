Feature: cashtrack has rest api
  In order to presen the financial data
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


    Scenario: CashFlow returns correct test data
      When I am on "/api/cashflow?month=2013-10-01"
      Then I should also see these cash entries:
        |name|accountId|amount|type|month|
        |1-account|1   |1893.00|expense|2013-10-01|
        |10-account|10   |5283.00|expense|2013-10-01|


    Scenario: Balance returns correct test data
      When I am on "/api/balance?month=2013-09-01"
      Then I should have credit "0", debit "0"
      And the account "47" with debit "102978.00" and credit "0"



    Scenario: Account returns correct test data
      When I am on "/api/account/1"
      Then I should get:
      |id|name|type|
      |1|1-account|expense|

@transaction
  Scenario: Transaction returns correct test data
    When I am on "/api/transaction?month=2013-10-01"
    Then I should get the transactions:
      |id|amount|fromAccount|toAccount|description|date|reference|modified_timestamp|
      |294|1719|49|2|294-description|2013-10-27||2013-10-27 18:23:11|
      |295|1998|49|46|295-description|2013-10-27||2013-10-27 18:23:11|
      |296|1833|49|4|296-description|2013-10-23||2013-10-27 18:25:55|
      |297|2174|49|46|297-description|2013-10-23||2013-10-27 18:25:55|
      |334|2370|48|47|334-description|2013-10-01|167EVST132740008|2013-11-17 18:24:13|
      |336|2328|47|14|336-description|2013-10-01|167EINT132740317|2013-11-17 18:24:13|
      |337|1532|47|14|337-description|2013-10-14|167EINT132870312|2013-11-17 18:24:13|
