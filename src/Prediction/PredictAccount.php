<?php
namespace Prediction;

use Finance\Account\Account;
use Finance\Account\AccountBalanceInterface;
use Finance\Transaction\TransactionRepositoryInterface;
use Refactoring\Time\Interval;
use Refactoring\Time\Interval\LastMonth;

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
        $entries = $this->balance->totalFor($this->getInterval());

        if (count($entries) > 2) {
            return array_reduce(
                $entries,
                function($total, $item) {
                     return $total + $item->amount;
                },
                0
            ) / count($entries);
        }

        return 0;
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

}
