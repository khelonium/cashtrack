<?php
namespace Prediction;

use Finance\Cashflow\MonthTotal;

trait BuildsCashtrack
{
    /**
     * @param $amount
     * @param $date
     * @return MonthTotal
     */
    protected function cashtrackWith($amount, $date)
    {
        $cashEntry = new MonthTotal();
        $cashEntry->accountId = 1;
        $cashEntry->amount = $amount;
        $cashEntry->type = 'expense';
        $cashEntry->month = $date;
        return $cashEntry;
    }
}