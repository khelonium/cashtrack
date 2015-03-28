<?php
namespace Watch;

use Finance\Account\Account;
use Finance\Account\AccountSum;
use Refactoring\Time\Interval\ThisMonth;
use Watch\Exception\NoAccountSet;

class Overflow
{
    const WARNING_LIMIT = 0.75;

    /**
     * @var AccountSum
     */
    private $accountSum;

    /**
     * @var Account
     */
    private $account;

    public function __construct(AccountSum $accountSum)
    {
        $this->accountSum = $accountSum;
    }

    public function isAbove($limit)
    {
        $collection = $this->accountSum->forAccount($this->getAccount())->totalFor(new ThisMonth());

        if ($collection->isEmpty()) {
            return false;
        }

        return   $collection->first()->amount  > $limit;

    }

    public function account(Account $account)
    {
        $overflow = clone $this;

        $overflow->setAccount($account);
        return $overflow;
    }

    private function setAccount(Account $account)
    {
        $this->account = $account;
    }

    private function getAccount()
    {
        if (null == $this->account) {
            throw new NoAccountSet();
        }

        return $this->account;
    }

    public function isAlmostAbove($amount)
    {
        return $this->isAbove(self::WARNING_LIMIT * $amount);
    }
}