Feature: Bank exports can be imported in UI


  Scenario: I see the option
    Given I am on the homepage
    When I follow "Import"
    Then I should see "Upload your file"

  Scenario: I can upload a file
    Given I am on "/import"
    And there are some merchants added
    When I attach the file "RO81BTRL06601201514881XX-01.01.2015-31.01.2015.csv" to "transaction_file"
    Then the file should be uploaded


  Scenario: I can import a transaction
     Given I am on "/import"
     And there are some merchants added
     When I attach the export file "RO81BTRL06601201514881XX-01.01.2015-31.01.2015.csv" to "transaction_file"
     And I press "Upload"
     Then I should be on "/import/done"
     Then I should see "Imported transactions"
     And I should see "Abonament BT 24"
     And I should see "2015-01-31"
     And I should see "ELECTRICA FURNIZARE"
     And I should see "162.43"


   Scenario: You can not just access process or done
     Given I am on "/import/process"
     Then I should be on "/import"
     Given I am on "/import/done"
     Then I should be on "/import"




