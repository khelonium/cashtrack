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
        $summaries = $this->balance->totalFor($this->getInterval());

        if (count($summaries) < 3) {
            return 0;
        }

        $sort = function ($a, $b) {
            if ($a->month == $b->month) {
                return 0;
            }

            return ($a->month > $b->month) ? -1 : 1;
        };

        $sorted = $summaries->sort($sort);

        $currentCadence = ((new \DateTime())->diff(new  \DateTime($sorted->first()->month))->format('%a')) / 30;

        $cadence = $this->buildCadence($sorted);

        if ($cadence->getCadence() > $currentCadence) {
            return 0;
        }

        return $this->getAmountAverage($sorted);
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
     * @param $summaries
     * @return float
     */
    protected function getAmountAverage($summaries)
    {
        return $summaries->reduce(function ($total, $item) {
            return $total + $item->amount;
        }, 0) / count($summaries);
    }

    /**
     * @param $sorted
     * @return Cadence
     */
    protected function buildCadence($sorted)
    {
        $dates = $sorted->map(
            function ($item) {
                return new \DateTime($item->month);
            }
        );

        $dates->merge(new Collection([new \DateTime()]));


        $cadence = new Cadence(
            $dates
        );

        return $cadence;
    }

    public function getCadence()
    {
        return $this->buildCadence($this->balance->totalFor($this->getInterval()))->getCadence();
    }

}
