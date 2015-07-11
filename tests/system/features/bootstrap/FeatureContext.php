<?php

require_once __DIR__ . '/../../../bootstrap.php';


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
        echo "Beforescenario \n";
        foreach ($this->getTransactionRepo()->all() as $transaction) {
            $this->getTransactionRepo()->delete($transaction);
        }

        foreach ($this->getMerchantRepo()->all() as $merchant) {
            $this->getMerchantRepo()->delete($merchant);
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
            ['description' => 'transaction 1', 'amount' => 100, 'to_account' => 86, 'date' => '2014-01-01']
        );
        $transaction->create(
            ['description' => 'transaction 1', 'amount' => 50, 'to_account' => 87, 'date' => '2014-01-01']
        );
        $transaction->create(
            ['description' => 'transaction 1', 'amount' => 100, 'to_account' => 87, 'date' => '2014-01-01']
        );

        $transaction->create(
            ['description' => 'transaction 1', 'amount' => 500, 'to_account' => 87, 'date' => '2014-02-01']
        );
        $transaction->create(
            ['description' => 'transaction 1', 'amount' => 100, 'to_account' => 87, 'date' => '2014-02-01']
        );
        $transaction->create(
            ['description' => 'transaction 1', 'amount' => 100, 'to_account' => 86, 'date' => '2014-02-28']
        );
        $transaction->create(
            ['description' => 'transaction 1', 'amount' => 100, 'to_account' => 86, 'date' => '2014-02-28']
        );
        $transaction->create(
            ['description' => 'transaction 1', 'amount' => 100, 'to_account' => 87, 'date' => '2015-01-01']
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
     * @return \Database\Merchant\Repository
     */
    protected function getMerchantRepo()
    {
        return TestBootstrap::get('\Database\Merchant\Repository');
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

        if (count($json) == 0) {
            throw new \Exception("There are no entries");
        }


        foreach ($json as $unit) {
            if ($expected[$unit->name] != $unit->amount) {
                throw new \Exception("{$unit->name} not sa expected");
            }
        }
    }

    /**
     * @Given /^there are some transactions which exceed the monthly limit$/
     */
    public function thereAreSomeTransactionsWhichExceedTheMonthlyLimit()
    {
        $transaction = $this->getTransactionRepo();

        $transaction->create(
            [
                'description' => 'transaction 1',
                'amount' => \Jobs\CheckMonthly::MONTH_LIMIT,
                'to_account' => 86,
                'date' => (new DateTime())->format('Y-m-d')
            ]
        );

        $transaction->create(
            [
                'description' => 'transaction 1',
                'amount' => \Jobs\CheckMonthly::MONTH_LIMIT,
                'to_account' => 86,
                'date' => (new DateTime())->format('Y-m-d')
            ]
        );

    }

    private $last = null;

    /**
     * @When /^the monthly job runs$/
     */
    public function theMonthlyJobRuns()
    {
        $job = new \Jobs\Double\MonthCheckDouble();
        $job->setUp();
        $job->perform();
        $this->last = $job;
    }

    /**
     * @Then /^the monthly notification is triggered$/
     */
    public function theMonthlyNotificationIsTriggered()
    {
        if (!$this->last->excessNotified) {
            throw new \Exception("Notification was not triggered");
        };
    }

    /**
     * @Given /^there are some transactions which don't exceed monthly limit$/
     */
    public function thereAreSomeTransactionsWhichDonTExceedMonthlyLimit()
    {
        $transaction = $this->getTransactionRepo();

        $transaction->create(
            [
                'description' => 'transaction 1',
                'amount' => \Jobs\CheckMonthly::MONTH_LIMIT - 1,
                'to_account' => 86,
                'date' => (new DateTime())->format('Y-m-d')
            ]
        );

    }

    /**
     * @Then /^the monthly notification is not triggered$/
     */
    public function theMonthlyNotificationIsNotTriggered()
    {
        if ($this->last->excessNotified) {
            throw new \Exception("Notification was  triggered and should have not");
        };
    }

    /**
     * @Given /^there are some transactions which do not exceed the warning limit$/
     */
    public function thereAreSomeTransactionsWhichDoNotExceedTheWarningLimit()
    {
        $transaction = $this->getTransactionRepo();

        $transaction->create(
            [
                'description' => 'transaction 1',
                'amount' => 0.4 * \Jobs\CheckMonthly::MONTH_LIMIT,
                'to_account' => 86,
                'date' => (new DateTime())->format('Y-m-d')
            ]
        );
    }

    /**
     * @Given /^the monthly warning notification is not triggered$/
     */
    public function theMonthlyWarningNotificationIsNotTriggered()
    {
        if ($this->last->almostNotified) {
            throw new \Exception("Warning notification was  triggered and should have not");
        };
    }

    /**
     * @Given /^there are some transactions which exceed the warning threshold$/
     */
    public function thereAreSomeTransactionsWhichExceedTheWarningThreshold()
    {
        $transaction = $this->getTransactionRepo();

        $transaction->create(
            [
                'description' => 'transaction 1',
                'amount' => 0.9 * \Jobs\CheckMonthly::MONTH_LIMIT,
                'to_account' => 86,
                'date' => (new DateTime())->format('Y-m-d')
            ]
        );
    }

    /**
     * @Then /^the monthly warning notification is triggered$/
     */
    public function theMonthlyWarningNotificationIsTriggered()
    {
        if (!$this->last->almostNotified) {
            throw new \Exception("Warning notification was  not triggered");
        };
    }

    /**
     * @Given /^there are some transactions which exceed the weekly limit$/
     */
    public function thereAreSomeTransactionsWhichExceedTheWeeklyLimit()
    {
        $transaction = $this->getTransactionRepo();

        $transaction->create(
            [
                'description' => 'transaction 1',
                'amount' => \Jobs\CheckWeekly::WEEKLY_LIMIT + 100,
                'to_account' => 86,
                'date' => (new DateTime())->format('Y-m-d')
            ]
        );

        $transaction->create(
            [
                'description' => 'transaction 1',
                'amount' => \Jobs\CheckWeekly::WEEKLY_LIMIT + 100,
                'to_account' => 86,
                'date' => (new DateTime())->format('Y-m-d')
            ]
        );
    }

    /**
     * @When /^the weekly job runs$/
     */
    public function theWeeklyJobRuns()
    {
        $this->last = new \Jobs\Double\WeekCheckDouble();
        $this->last->setUp();
        $this->last->perform();
    }

    /**
     * @Then /^the weekly notification is triggered$/
     */
    public function theWeeklyNotificationIsTriggered()
    {
        if (!$this->last->excessNotified) {
            throw new \Exception("Weekly limit notification  was  not triggered");
        };
    }

    /**
     * @Given /^there are some transactions which do not exceed the weekly warning limit$/
     */
    public function thereAreSomeTransactionsWhichDoNotExceedTheWeeklyWarningLimit()
    {
        $transaction = $this->getTransactionRepo();

        $transaction->create(
            [
                'description' => 'transaction 1',
                'amount' => \Jobs\CheckWeekly::WEEKLY_LIMIT * 0.3,
                'to_account' => 86,
                'date' => (new DateTime())->format('Y-m-d')
            ]
        );
    }

    /**
     * @Then /^the weekly notification is not triggered$/
     */
    public function theWeeklyNotificationIsNotTriggered()
    {
        if ($this->last->excessNotified || $this->last->almostNotified) {
            throw new \Exception("Weekly limit : There should be no notifications");
        };
    }

    /**
     * @Given /^there are some merchants added$/
     */
    public function thereAreSomeMerchantsAdded()
    {

        $this->getMerchantRepo()->create(
            [
                'name' => 'unknown',
                'identifier' => 'unknown',
                'accountId' => 41
            ]
        );

    }

    /**
     * @Then /^the file should be uploaded$/
     */
    public function theFileShouldBeUploaded()
    {
    }

    /**
     * @When /^(?:|I )attach the export file "(?P<path>[^"]*)" to "(?P<field>(?:[^"]|\\")*)"$/
     */
    public function iAttachTheExportFileTo($file, $field)
    {
        $file = dirname(__DIR__).DIRECTORY_SEPARATOR.$file;
        $this->attachFileToField($field, $file);
    }

}


