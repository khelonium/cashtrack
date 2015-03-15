<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/15/15
 * Time: 9:10 PM
 */

namespace Prediction\Double;


use Finance\Account\Account;
use Finance\Account\AccountBalanceInterface;
use Finance\Cashflow\MonthSummary;
use Refactoring\Time\Interval;

class AccountBalanceStub implements AccountBalanceInterface
{
    public $willReturn = [];

    public function __construct(Account $account)
    {
        // TODO: Implement __construct() method.
    }


    public function totalFor(Interval $interval)
    {

        $out = [];

        /** @var MonthSummary $cash */
        foreach ($this->willReturn as $cash) {
            if ($interval->contains(new \DateTime($cash->month))) {
                $out[] = $cash;
            }
        }

        return $out;
    }

}
