<?php
namespace Prediction;

use Finance\Cashflow\AccountTotal;

trait BuildsCashtrack
{
    /**
     * @param $amount
     * @param $date
     * @return AccountTotal
     */
    protected function cashtrackWith($amount, $date)
    {
        $cashEntry = new AccountTotal();
        $cashEntry->accountId = 1;
        $cashEntry->amount = $amount;
        $cashEntry->type = 'expense';
        $cashEntry->month = $date;
        return $cashEntry;
    }
}