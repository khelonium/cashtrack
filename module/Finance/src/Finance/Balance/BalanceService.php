<?php


namespace Finance\Balance;

use Finance\AccountValue\AccountValueFactoryAwareInterface;
use Finance\AccountValue\AccountValueFactoryAwareTrait;
use Finance\Balance\Specification\ClosedMonth;
use Refactoring\Interval\IntervalInterface;
use Refactoring\Interval\SpecificMonth;
use Zend\Db\Adapter\Adapter;

/**
 * Class BalanceService
 * Computes transients balances for any interval
 * @package Finance\Balance
 */
class BalanceService implements AccountValueFactoryAwareInterface
{

    use AccountValueFactoryAwareTrait;

    /**
     * @var null|\Zend\Db\Adapter\Adapter
     */
    private $adapter = null;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }


    /**
     * This is not in repository or factory because we don't know if we want an
     * open balance or a closed balance.
     * yet.
     * @param \Refactoring\Interval\SpecificMonth $month
     * @param SpecificMonth $month
     * @return \Finance\Balance\ClosedBalance|\Finance\Balance\OpenBalance
     */
    public function getBalance(SpecificMonth $month)
    {
        $isClosedMonth = new ClosedMonth();

        if ($isClosedMonth->isSatisfiedBy($month)) {
            return new ClosedBalance();
        } else {
            return new OpenBalance($month, $this->getAccountValueFactory());
        }
    }



}