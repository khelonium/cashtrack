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
use Finance\Cashflow\MonthTotal;
use Finance\Cashflow\MonthTotalCollection;
use Refactoring\Time\Interval;

class AccountBalanceStub implements AccountBalanceInterface
{
    public $willReturn = [];

    public function __construct(Account $account)
    {
        // TODO: Implement __construct() method.
    }


    /**
     * @param Interval $interval
     * @return MonthTotalCollection
     */
    public function totalFor(Interval $interval)
    {

        $out = [];

        /** @var MonthTotal $cash */
        foreach ($this->willReturn as $cash) {
            if ($interval->contains(new \DateTime($cash->month))) {
                $out[] = $cash;
            }
        }

        return new MonthTotalCollection($out);

    }

}
