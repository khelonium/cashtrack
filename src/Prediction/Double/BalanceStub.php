<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/15/15
 * Time: 9:10 PM
 */

namespace Prediction\Double;


use Finance\Account\Account;
use Finance\Account\AccountSum;
use Finance\Cashflow\AccountTotal;
use Library\Collection;
use Refactoring\Time\Interval;

class BalanceStub implements AccountSum
{
    public $willReturn = [];

    public function __construct(Account $account)
    {
        // TODO: Implement __construct() method.
    }


    /**
     * @param Interval $interval
     * @return \Library\Collection
     */
    public function totalFor(Interval $interval)
    {

        $out = [];

        /** @var AccountTotal $cash */
        foreach ($this->willReturn as $cash) {
            if ($interval->contains(new \DateTime($cash->month))) {
                $out[] = $cash;
            }
        }

        return new Collection($out);

    }

}
