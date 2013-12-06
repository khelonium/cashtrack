<?php


namespace Finance\Balance;

use Finance\AccountValue\AccountValueFactoryAwareInterface;
use Finance\AccountValue\AccountValueFactoryAwareTrait;
use Finance\Balance\Specification\BalanceCanBeClosed;
use Finance\Balance\Specification\ClosedMonth;
use Finance\Month\MonthWithTransactionSpecification;
use Finance\Transaction\Transaction;
use Finance\Transaction\TransactionRepositoryAwareInterface;
use Finance\Transaction\TransactionRepositoryAwareTrait;
use Refactoring\Interval\SpecificMonth;

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
     *
     * Used to mark a month as closed
     * @param SpecificMonth $month
     * @return ClosedBalance|OpenBalance
     */
    public function closeMonth(SpecificMonth $month)
    {



        $monthHasTransaction = new MonthWithTransactionSpecification();
        $monthHasTransaction->setTransactionRepository($this->getTransactionRepository());

        if (!$monthHasTransaction->isSatisfiedBy($month)) {
            throw new \DomainException("There are no transactions in this month");
        }

        $canBeClosed = new BalanceCanBeClosed();

        $balance = $this->getBalance($month);

        if (!$canBeClosed->isSatisfiedBy($balance)) {
            throw new \DomainException("This month can not be closed");
        }

        $buffers = new SubsetBalance($balance, 'buffer');

        $next = clone $month->getEnd();

        $next->add(new \DateInterval('P1D'));
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