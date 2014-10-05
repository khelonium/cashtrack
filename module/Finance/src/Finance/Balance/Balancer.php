<?php


namespace Finance\Balance;

use Finance\AccountValue\AccountValueFactoryAwareInterface;
use Finance\AccountValue\AccountValueFactoryAwareTrait;
use Finance\Balance\History\BalanceRepositoryAwareInterface;
use Finance\Balance\History\BalanceRepositoryAwareTrait;
use Finance\Balance\History\History;
use Finance\Balance\Specification\BalanceCanBeClosed;
use Finance\Balance\Specification\ClosedMonth;
use Finance\Transaction\Specification\MonthWithTransactionSpecification;
use Finance\Transaction\Transaction;
use Finance\Transaction\TransactionRepositoryAwareInterface;
use Finance\Transaction\TransactionRepositoryAwareTrait;
use Refactoring\Interval\SpecificMonth;

/**
 * Class BalanceService
 * Computes  balances for any interval
 * Boundary between delivery and business
 * @package Finance\Balance
 */
class Balancer implements
    AccountValueFactoryAwareInterface,
    TransactionRepositoryAwareInterface,
    BalanceRepositoryAwareInterface
{

    use AccountValueFactoryAwareTrait;
    use TransactionRepositoryAwareTrait;
    use BalanceRepositoryAwareTrait;

    /**
     *
     * Used to mark a month as closed
     * @param SpecificMonth $month
     * @throws \DomainException
     * @return ClosedBalance|OpenBalance
     */
    public function closeMonth(SpecificMonth $month)
    {



        $monthHasTransaction = new MonthWithTransactionSpecification();
        $monthHasTransaction->setTransactionRepository($this->getTransactionRepository());

        if (!$monthHasTransaction->isSatisfiedBy($month)) {
            throw new \DomainException("There are no transactions in this month ".$month->getStart()->format('Y-m-d'));
        }

        $canBeClosed = new BalanceCanBeClosed();
        $balance = $this->getBalance($month);


        if (!$canBeClosed->isSatisfiedBy($balance)) {
            throw new \DomainException("This month can not be closed");
        }

        $closedMonth = new ClosedMonth($this->getBalanceRepository());

        if ($closedMonth->isSatisfiedBy($month)) {
            throw new \DomainException("Month ".$month->getStart()->format('Y-m-d')." already closed");
        }



        $buffers = new SubsetBalance($balance, 'buffer');
        $next    = clone $month->getEnd();

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


        $history = new History();
        $history['month'] = $month->getStart()->format('Y-m-d');
        $history['balance'] = $balance->getBalance();
        $history['debit']   = $balance->getDebit();
        $history['credit']  = $balance->getCredit();

        $this->getBalanceRepository()->add($history);

        return $this->getBalance($month);

    }

    /**
     * @param \Refactoring\Interval\SpecificMonth $month
     * @param SpecificMonth $month
     * @return \Finance\Balance\ClosedBalance|\Finance\Balance\OpenBalance
     */
    public function getBalance(SpecificMonth $month)
    {
        $isClosedMonth = new ClosedMonth($this->getBalanceRepository());

        if ($isClosedMonth->isSatisfiedBy($month)) {
            return new ClosedBalance($month, $this->getAccountValueFactory());
        } else {
            return new OpenBalance($month, $this->getAccountValueFactory());
        }
    }
}
