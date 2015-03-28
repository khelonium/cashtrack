<?php
namespace Watch;

use Finance\Account\Account;
use Finance\Account\AccountSum;
use Refactoring\Time\Interval\IntervalInterface;
use Refactoring\Time\Interval\ThisMonth;
use Watch\Exception\NoAccountSet;

class AccountOverflow
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
    /**
     * @var IntervalInterface
     */
    private $strategy;

    public function __construct(AccountSum $accountSum, IntervalInterface $strategy = null)
    {
        $this->accountSum = $accountSum;

        $this->strategy = $strategy;

        $this->strategy or $this->strategy = new ThisMonth();
    }

    public function isAbove($limit)
    {
        $collection = $this->accountSum->forAccount($this->getAccount())->totalFor($this->strategy);

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