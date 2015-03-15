<?php
namespace Prediction;

require_once 'BuildsCashtrack.php';

use Database\Transaction\Repository;
use Finance\Account\Account;
use Finance\Cashflow\MonthSummary;
use Prediction\Double\AccountBalanceStub;
use Refactoring\Time\Interval\LastMonth;

class AccountPredictionTest extends \PHPUnit_Framework_TestCase
{
    use BuildsCashtrack;
    /**
     * @var AccountBalanceStub
     */
    private $accountBalance;

    /**
     * @var PredictAccount
     */
    private $prediction;

    /**
     * @before
     */
    public function itCanConstruct()
    {
        $account = new Account();
        $account->id = 1;

        $this->accountBalance = new AccountBalanceStub($account);

        $this->prediction = new PredictAccount($this->accountBalance);

    }

    /**
     * @test
     */
    public function withNoTransactionInPreviousMonthsThePredictionisZero()
    {
        $cashEntry = $this->cashtrackWith(100, (new \DateTime())->format('Y-m-01'));

        $this->assertEquals(0, $this->prediction->thisMonth());

        $this->accountBalance->willReturn = [$cashEntry];

        $this->assertEquals(0, $this->prediction->thisMonth());

    }

    /**
     * @test
     */
    public function ifExpensesDoNotShowInMoreThanThreeMonthsThereIsNoPrediction()
    {

        $first  =  (new LastMonth())->getStart();
        $second = (new LastMonth())->getStart()->sub(new \DateInterval('P1M'));

        $this->accountBalance->willReturn = [
            $this->cashtrackWith(100, $first->format('Y-m-01')),
            $this->cashtrackWith(100, $second->format('Y-m-01')),

        ];

        $this->assertEquals(0, $this->prediction->thisMonth());

    }

    /**
     * @test
     */
    public function entriesOlderThan1YearAreIgnored()
    {
        $first  =  (new LastMonth())->getStart();
        $second = (new LastMonth())->getStart()->sub(new \DateInterval('P1M'));
        $third =  clone $second;
        $third->sub(new \DateInterval('P1M'));
        $forth =  clone $second;
        $forth->sub(new \DateInterval('P1Y'));

        $this->accountBalance->willReturn = [
            $this->cashtrackWith(100, $first->format('Y-m-01')),
            $this->cashtrackWith(200, $second->format('Y-m-01')),
            $this->cashtrackWith(600, $third->format('Y-m-01')),
            $this->cashtrackWith(300, $forth->format('Y-m-01')),

        ];

        $this->assertEquals(300, $this->prediction->thisMonth());
    }


    /**
     * @test
     */
    public function withMoreThanThreeMonthsThereWillBeAPrediction()
    {
        $first  =  (new LastMonth())->getStart();
        $second = (new LastMonth())->getStart()->sub(new \DateInterval('P1M'));
        $third =  clone $second;
        $third->sub(new \DateInterval('P1D'));

        $this->accountBalance->willReturn = [
            $this->cashtrackWith(100, $first->format('Y-m-01')),
            $this->cashtrackWith(200, $second->format('Y-m-01')),
            $this->cashtrackWith(300, $third->format('Y-m-01')),

        ];

        $this->assertEquals(200, $this->prediction->thisMonth());
    }


    /**
     * @test
     */
    public function forACadenceOfTwoAndSummaryLastMonthThereIsNoPrediction()
    {

        $first  =  (new LastMonth())->getStart();
        $second = (new LastMonth())->getStart()->sub(new \DateInterval('P2M'));
        $third =  clone $second;
        $third->sub(new \DateInterval('P3M'));

        $this->accountBalance->willReturn = [
            $this->cashtrackWith(100, $first->format('Y-m-01')),
            $this->cashtrackWith(200, $second->format('Y-m-01')),
            $this->cashtrackWith(300, $third->format('Y-m-01')),

        ];

        $this->assertEquals(0, $this->prediction->thisMonth());

    }




    /**
     * @return Repository $repo
     */
    protected function getTransactionRepository()
    {
        return \TestBootstrap::get('\Database\Transaction\Repository');
    }

    /**
     * @param $transaction
     */
    protected function addTransaction($transaction)
    {
        $repo = $this->getTransactionRepository();

        $repo->add(
            $transaction
        );
    }


}




