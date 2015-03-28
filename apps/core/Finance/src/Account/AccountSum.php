<?php
namespace Finance\Account;

use Library\Collection;
use Refactoring\Time\Interval;

interface AccountSum
{

    /**
     * @param Interval $interval
     * @return Collection
     */
    public function totalFor(Interval $interval);

    public function setAccount(Account $account = null);

    /**
     * Side free effect.
     * @param Account $account
     * @return AccountSum
     */
    public function forAccount(Account $account);
}
