<?php
namespace Prediction;

use Finance\Account\Account;
use Finance\Account\AccountBalanceInterface;
use Finance\Cashflow\MonthTotal;
use Refactoring\Time\Interval;
use Refactoring\Time\Interval\LastMonth;
use Refactoring\Time\Interval\ThisMonth;

class PredictAccount
{
    /**
     * @var Account
     */
    /**
     * @var AccountBalanceInterface
     */
    private $balance;

    public function __construct(AccountBalanceInterface $balance)
    {
        $this->balance = $balance;
    }

    public function thisMonth()
    {
        $summaries = $this->balance->totalFor($this->getInterval());

        if (count($summaries) < 3) {
            return 0;
        }

        $sort = function ($a, $b) {
            if ($a == $b) {
                return 0;
            }

            return ($a < $b) ? -1 : 1;
        };

         $summaries =  $summaries->sort($sort);

        $currentCadence = ((new \DateTime())->diff(new  \DateTime($summaries->first()->month))->format('%a')) / 30;


        $cadence = new Cadence($summaries);

        if ($cadence->getCadence() > $currentCadence) {
            return 0;
        }

        return $this->getAmountAverage($summaries);
    }

    /**
     * @return LastMonth
     */
    protected function getInterval()
    {
        $end = (new LastMonth())->getStart();
        $start = clone $end;
        $start->sub(new \DateInterval('P1Y'));
        return new Interval($start, $end);
    }

    /**
     * @param $summaries
     * @return float
     */
    protected function getAmountAverage($summaries)
    {
        return $summaries->reduce(function ($total, $item) {
            return $total + $item->amount;
        }, 0) / count($summaries);
    }

}
