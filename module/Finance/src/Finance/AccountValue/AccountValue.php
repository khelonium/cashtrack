<?php


namespace Finance\AccountValue;
use Finance\Account\Account;
use Refactoring\Interval\IntervalInterface;
use Refactoring\Interval;

/**
 * Class AccountValue
 * This class  is used to represent the credit and debit of an account over a period of time
 * @package Finance\AccountValue
 */
class AccountValue extends  \ArrayObject
{

    /**
     * @var IntervalInterface
     */
    private $interval = null;

    /**
     * @var Account
     */
    private $account = null;

    /***
     * @var null
     */
    private $debit = null;

    /**
     * @var null
     */
    private $credit = null;


    /**
     * @param Interval $interval
     * @param Account $account
     * @param int $debit
     * @param int $credit
     */
    public function __construct(Interval $interval, Account $account, $debit = 0, $credit = 0)
    {
        //fixme add assertions

        if ($debit === null ) {
            $debit = 0;
        }

        if ($credit === null ) {
            $credit = 0;
        }

        $this->credit   = $credit;
        $this->debit    = $debit;
        $this->account  = $account;
        $this->interval = $interval;

    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return IntervalInterface
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @return float
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * @return float
     */
    public function getDebit()
    {

        return $this->debit;
    }


    public function getBalance()
    {
        return $this->getCredit() - $this->getDebit();
    }


    /**
     * fixme this representation has to be moved in a helper in api
     * @return array
     */
    public function toArray()
    {
        $data = array (
            'credit' => $this->credit,
            'debit'  => $this->debit,
            'balance' => $this->getBalance(),
            'idAccount' => $this->getAccount()->id,
            'name'      => $this->getAccount()->name,
            'type'      => $this->getAccount()->type
        );
        return $data;
    }
}