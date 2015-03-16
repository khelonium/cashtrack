<?php
namespace Finance\Account;

use Finance\Cashflow\MonthTotal;
use Finance\Cashflow\MonthTotalCollection;
use Refactoring\Time\Interval;

interface AccountBalanceInterface
{
    public function __construct(Account $account);

    /**
     * @param Interval $interval
     * @return MonthTotalCollection
     */
    public function totalFor(Interval $interval);
}