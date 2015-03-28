<?php
namespace Finance\Account;

use Library\Collection;
use Refactoring\Time\Interval;

interface AccountSum
{
    public function __construct(Account $account);

    /**
     * @param Interval $interval
     * @return Collection
     */
    public function totalFor(Interval $interval);
}