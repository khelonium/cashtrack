<?php
namespace Finance\Account;

use Finance\Cashflow\MonthSummary;
use Refactoring\Time\Interval;

interface AccountBalanceInterface
{
    public function __construct(Account $account);

    /**
     * @param Interval $interval
     * @return []
     */
    public function totalFor(Interval $interval);
}