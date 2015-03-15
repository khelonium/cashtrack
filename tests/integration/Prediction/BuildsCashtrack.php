<?php
namespace Prediction;

use Finance\Cashflow\MonthSummary;

trait BuildsCashtrack
{
    /**
     * @param $amount
     * @param $date
     * @return MonthSummary
     */
    protected function cashtrackWith($amount, $date)
    {
        $cashEntry = new MonthSummary();
        $cashEntry->accountId = 1;
        $cashEntry->amount = $amount;
        $cashEntry->type = 'expense';
        $cashEntry->month = $date;
        return $cashEntry;
    }
}