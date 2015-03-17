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
    public function beforeScenario()
    {
        foreach ($this->getTransactionRepo()->all() as $transaction) {
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

        $transaction->create(
            ['description' => 'transaction 1', 'amount'  => 100,  'to_account'  => 86, 'date' => '2014-01-01']
        );
        $transaction->create(
            ['description' => 'transaction 1', 'amount'  => 50,  'to_account'  => 87, 'date' => '2014-01-01']
        );
        $transaction->create(
            ['description' => 'transaction 1', 'amount'  => 100,  'to_account'  => 87, 'date' => '2014-01-01']
        );

        $transaction->create(
            ['description' => 'transaction 1', 'amount'  => 500,  'to_account'  => 87, 'date' => '2014-02-01']
        );
        $transaction->create(
            ['description' => 'transaction 1', 'amount'  => 100,  'to_account'  => 87, 'date' => '2014-02-01']
        );
        $transaction->create(
            ['description' => 'transaction 1', 'amount'  => 100,  'to_account'  => 86, 'date' => '2014-02-28']
        );
        $transaction->create(
            ['description' => 'transaction 1', 'amount'  => 100,  'to_account'  => 86, 'date' => '2014-02-28']
        );
        $transaction->create(
            ['description' => 'transaction 1', 'amount'  => 100,  'to_account'  => 87, 'date' => '2015-01-01']
        );

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
        $this->checkResponseAgainst([
            'Category 1' => 100,
            'Category 2' => 150,

        ]);
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
        $expected = [
            'Category 1' => 200,
            'Category 2' => 600,

        ];

        $this->checkResponseAgainst($expected);

    }

    /**
     * @return \Database\Transaction\Repository
     */
    protected function getTransactionRepo()
    {
        /** @var \Database\Transaction\Repository $transaction */
        $transaction = TestBootstrap::get('\Database\Transaction\Repository');
        return $transaction;
    }

    /**
     * @When /^I check the month breakdown api for that given year$/
     */
    public function iCheckTheMonthBreakdownApiForThatGivenYear()
    {
        $this->visit('/api/breakdown/year/2014');
    }

    /**
     * @Then /^the expenses are grouped by category and year$/
     */
    public function theExpensesAreGroupedByCategoryAndYear()
    {
        $this->checkResponseAgainst([
            'Category 1' => 300,
            'Category 2' => 750,

        ]);
    }

    /**
     * @param $expected
     * @throws Exception
     */
    protected function checkResponseAgainst($expected)
    {

        $json = $this->getJson();

        if (count($json) ==0) {
            throw new \Exception("There are no entries");
        }

        foreach ($json as $unit) {
            if ($expected[$unit->name] != $unit->amount) {
                throw new \Exception("{$unit->name} not sa expected");
            }
        }
    }


}

