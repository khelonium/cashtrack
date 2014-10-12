<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends \Behat\MinkExtension\Context\MinkContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

    /**
     * @Given /^I should see a json response$/
     */
    public function iShouldSeeAJsonResponse()
    {
        $this->getJson();
    }

    /**
     * @Then /^I should also see these cash entries:$/
     */
    public function iShouldAlsoSeeTheseCashEntries(TableNode $table)
    {
        $response = $this->getJson();
        foreach ($table->getHash() as $entry) {

            $responseEntry = array_shift($response);

            foreach ($entry as $key => $val) {
                if ($entry[$key] !== $responseEntry->$key) {
                    throw new \Exception("$key does not match");
                }
            }
        }
    }

    private function getJson()
    {
        $response = $this->getSession()->getPage()->getContent();
        return \Zend\Json\Json::decode($response);
    }

    /**
     * @Then /^I should have credit "([^"]*)", debit "([^"]*)"$/
     */
    public function iShouldHaveCreditDebit($credit, $debit)
    {
        $balance = $this->getJson();

        if ($balance->credit != $credit) {
            throw new \Exception("Credit mismatch");
        }


        if ($balance->debit != $debit) {
            throw new \Exception("Debit mismatch");
        }

    }

    /**
     * @Given /^the account "([^"]*)" with debit "([^"]*)" and credit "([^"]*)"$/
     */
    public function theAccountWithDebitAndCredit($idAccount, $debit, $credit)
    {
        $balance = $this->getJson();

        $account = $balance->accounts[0];

        if (
            ($account->idAccount != $idAccount) ||
            ($account->debit != $debit)         ||
            ($account->credit != $credit)

        ) {
            throw new \Exception(var_export($account,true));
        }
    }

    /**
     * @Then /^I should get:$/
     */
    public function iShouldGet(TableNode $table)
    {
        $response = $this->getJson();
        foreach ($table->getHash() as $entry) {


            foreach ($entry as $key => $val) {
                if ($entry[$key] !== $response->$key) {
                    throw new \Exception("$key does not match");
                }
            }
        }
    }

    /**
     * @Then /^I should get the transactions:$/
     */
    public function iShouldGetTheTransactions(TableNode $table)
    {
        $response = $this->getJson();
        foreach ($table->getHash() as $entry) {

            $responseEntry = array_shift($response);

            foreach ($entry as $key => $val) {
                if ($entry[$key] !== $responseEntry->$key) {
                    throw new \Exception("$key does not match");
                }
            }
        }
    }

    /**
     * @Then /^I show last response$/
     */
    public function iShowLastResponse()
    {
        $this->printLastResponse();
    }


}
