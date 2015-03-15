<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/15/15
 * Time: 8:37 PM
 */

namespace Prediction;


use Database\Transaction\Repository\Specification;
use Finance\Account\Account;
use Refactoring\Time\Interval;

class AccountHistory extends Specification
{


    public function __construct(Account $account, Interval $interval)
    {
        $this->equalTo('toAccount', $account->id);
        $this->greaterOrEqual('date', $interval->getStart()->format('Y-m-d H:i:s'));
        $this->lessOrEqual('date', $interval->getEnd()->format('Y-m-d H:i:s'));
    }
}