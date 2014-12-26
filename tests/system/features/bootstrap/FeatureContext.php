<?php

require_once __DIR__.'/../../../bootstrap.php';


/**
 * Features context.
 */
class FeatureContext extends \Behat\MinkExtension\Context\MinkContext
{
    

    /**
     * @beforeScenario
     */
    public  function beforeScenario()
    {
        foreach($this->getTransactionRepo()->all() as $transaction) {
            $this->getTransactionRepo()->delete($transaction);
        }
    }

    /**
     * @Given /^I should see a json response$/
     */
    public function iShouldSeeAJsonResponse()
    {
        $this->getJson();
    }


    private function getJson()
    {
        $response = $this->getSession()->getPage()->getContent();
        return \Zend\Json\Json::decode($response);
    }



    /**
     * @Then /^I show last response$/
     */
    public function iShowLastResponse()
    {
        $this->printLastResponse();
    }

    /**
     * @Given /^there are some transactions$/
     */
    public function thereAreSomeTransactionsInAWeek()
    {
        $transaction = $this->getTransactionRepo();

        $transaction->fastAdd(['description' => 'transaction 1', 'amount'  => 100,  'to_account'  => 86, 'date' => '2014-01-01']);
        $transaction->fastAdd(['description' => 'transaction 1', 'amount'  => 50,  'to_account'  => 87, 'date' => '2014-01-01']);
        $transaction->fastAdd(['description' => 'transaction 1', 'amount'  => 100,  'to_account'  => 87, 'date' => '2014-01-01']);
        $transaction->fastAdd(['description' => 'transaction 1', 'amount'  => 500,  'to_account'  => 87, 'date' => '2014-02-01']);
        $transaction->fastAdd(['description' => 'transaction 1', 'amount'  => 100,  'to_account'  => 87, 'date' => '2014-02-01']);
        $transaction->fastAdd(['description' => 'transaction 1', 'amount'  => 100,  'to_account'  => 86, 'date' => '2014-02-28']);
        $transaction->fastAdd(['description' => 'transaction 1', 'amount'  => 100,  'to_account'  => 86, 'date' => '2014-02-28']);
        $transaction->fastAdd(['description' => 'transaction 1', 'amount'  => 100,  'to_account'  => 87, 'date' => '2015-01-01']);

    }

    /**
     * @When /^when I check the week breakdown api for that given week$/
     */
    public function whenICheckTheWeekBreakdownApiForThatGivenWeek()
    {
        $this->visit('/api/breakdown/week/2014/1');
    }

    /**
     * @Then /^the expenses are grouped by category and week$/
     */
    public function theExpensesAreGroupedByCategory()
    {
        $json = $this->getJson();
        $expected = [
            'Category 1' => 100,
            'Category 2' => 150,

        ];

        foreach ($json as $unit) {
            if ($expected[$unit->name] != $unit->amount ) {
                throw new \Exception("{$unit->name} not sa expected");
            }
        }
    }

    /**
     * @When /^I check the month breakdown api for that given month$/
     */
    public function iCheckTheMonthBreakdownApiForThatGivenMonth()
    {
        $this->visit('/api/breakdown/month/2014/2');
    }

    /**
     * @Then /^the expenses are grouped by category and month$/
     */
    public function theExpensesAreGroupedByCategoryAndMonth()
    {
        $json = $this->getJson();
        $expected = [
            'Category 1' => 200,
            'Category 2' => 600,

        ];

        if (count($json) ==0) {
            throw new \Exception("There are no entries");
        }

        foreach ($json as $unit) {
            if ($expected[$unit->name] != $unit->amount ) {
                throw new \Exception("{$unit->name} not as expected:{$expected[$unit->name]}: {$unit->amount}");
            }
        }    }

    /**
     * @return \Database\Transaction\Repository
     */
    protected function getTransactionRepo()
    {
        /** @var \Database\Transaction\Repository $transaction */
        $transaction = TestBootstrap::get('\Database\Transaction\Repository');
        return $transaction;
    }


}
