<?php
namespace Prediction;

use Finance\Account\Account;
use Finance\Account\AccountSum;
use Library\Collection;
use Refactoring\Time\Interval;
use Refactoring\Time\Interval\LastMonth;

class PredictAccount
{
    /**
     * @var Account
     */
    /**
     * @var AccountSum
     */
    private $balance;

    public function __construct(AccountSum $balance)
    {
        $this->balance = $balance;

    }

    public function thisMonth()
    {

        return $this->requiresNoPrediction() ? 0 : $this->getAverage();
    }

    /**
     * @return float
     * @internal param $summaries
     */
    protected function getAverage()
    {
        $summaries = $this->getPayments();

        return $summaries->reduce(function ($total, $item) {
            return $total + $item->amount;
        }, 0) / count($summaries);
    }


    public function getCadence()
    {
        return $this->buildCadence()->getCadence();
    }

    /**
     * @return Cadence
     * @internal param $sorted
     */
    protected function buildCadence()
    {

        return new Cadence(
            $this->balance->totalFor($this->getInterval())->map(
                function ($item) {
                    return new \DateTime($item->month);
                }
            )
        );

    }

    /**
     * @return LastMonth
     */
    protected function getInterval()
    {
        $end = (new LastMonth())->getEnd();
        $start = (new LastMonth())->getStart();
        $start->sub(new \DateInterval('P1Y'));
        return new Interval($start, $end);
    }

    /**
     * @return Collection
     */
    private function getPayments()
    {
        return $this->balance->totalFor($this->getInterval());
    }

    /**
     * @return bool
     * @internal param $monthsPassed
     * @internal param $cadence
     */
    private function requiresNoPrediction()
    {
        $monthsPassed = $this->monthsSinceLastPay();
        $cadence      = $this->getCadence();

        return
            ($cadence == 0 and $monthsPassed == 0) or
            ($this->looksLikeAbandonedAccount($monthsPassed, $cadence) ||
                $this->payedRecently($cadence, $monthsPassed));
    }


    /**
     * @return array
     * @internal param $summaries
     */
    private function monthsSinceLastPay()
    {
        $sort = function ($a, $b) {

            if ($a->month == $b->month) {
                return 0;
            }

            return ($a->month > $b->month) ? -1 : 1;
        };

        $sorted = $this->getPayments()->sort($sort);

        if ($sorted->count() < 3) {
            return 0;
        }

        $currentCadence = ((new \DateTime())->modify('first day of this month')
                ->diff(new  \DateTime($sorted->first()->month))->format('%a')) / 30;
        return round(10 * $currentCadence) / 10;
    }


    /**
     * @param $cadence
     * @param $monthsPassed
     * @return bool
     */
    private function payedRecently($cadence, $monthsPassed)
    {
        return $cadence > $monthsPassed;
    }

    /**
     * @param $monthsPassed
     * @param $cadence
     * @return float
     */
    private function looksLikeAbandonedAccount($monthsPassed, $cadence)
    {
        return ($monthsPassed / $cadence) > 4;
    }

}
