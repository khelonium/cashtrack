Feature: Bank exports can be imported in UI


  Scenario: I see the option
    Given I am on the homepage
    When I follow "Import"
    Then I should see "Upload your file"

    
   Scenario: I can upload a transaction
     Given I am on "/import"
     When I attach the file "sample.csv" to "transaction_file"
     And I press "Upload"
     Then I should be on "/import/done"
     Then I should see "Imported transactions"
     And I should see "Abonament BT 24"
     And I should see "2015-01-31"
     And I should see "1.24"
     And I should see "ELECTRICA FURNIZARE"
     And I should see "162.43"


   Scenario: You can not just access process or done
     Given I am on "/import/process"
     Then I should be on "/import"
     Given I am on "/import/done"
     Then I should be on "/import"




