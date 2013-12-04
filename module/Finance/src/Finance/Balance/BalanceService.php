<?php


namespace Finance\Balance;

use Finance\AccountValue\AccountValueFactoryAwareInterface;
use Finance\AccountValue\AccountValueFactoryAwareTrait;
use Finance\Balance\Specification\ClosedMonth;
use Finance\Transaction\Transaction;
use Finance\Transaction\TransactionRepositoryAwareInterface;
use Finance\Transaction\TransactionRepositoryAwareTrait;
use Refactoring\Interval\IntervalInterface;
use Refactoring\Interval\SpecificMonth;
use Zend\Db\Adapter\Adapter;

/**
 * Class BalanceService
 * Computes transients balances for any interval
 * @package Finance\Balance
 */
class BalanceService implements AccountValueFactoryAwareInterface, TransactionRepositoryAwareInterface
{

    use AccountValueFactoryAwareTrait;
    use TransactionRepositoryAwareTrait;

    /**
     * @var null|\Zend\Db\Adapter\Adapter
     */
    private $adapter = null;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }


    /**
     * Used to mark a month as closed
     * @param SpecificMonth $month
     * @return ClosedBalance|OpenBalance
     */
    public function closeMonth(SpecificMonth $month)
    {

        $balance = $this->getBalance($month);
        $buffers = new SubsetBalance($balance, 'buffer');


        $next = $month->getEnd()->add(new \DateInterval('P1D'));
        $date = $next->format('Y-m-d');

        foreach ($buffers->accounts() as $buffer) {
            $transaction                 = new Transaction();
            $transaction['amount']       = $buffer->getBalance();
            $transaction['date']         = $date;
            $transaction['reference']    = 'Previous month balance';
            $transaction['from_account'] =  55;
            $transaction['to_account']   =  $buffer->getAccount()['id'];
            $this->getTransactionRepository()->add($transaction);
        }

        return $this->getBalance($month);

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