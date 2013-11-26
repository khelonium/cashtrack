<?php
namespace Finance\Balance;

class Balance extends \Finance\Transaction\Transaction implements \Traversable
{

    private $defaultData = array (
        'credit' => 0,
        'debit'  => 0,
        'month'  => null


    );

    public function __construct($data = null)
    {
        $out = $this->defaultData;

        if ($data) {
            foreach ($data as $key => $value) {
                $out[$key] = $value;
            }
        }

        parent::__construct($out);
    }

    protected $map = array (
        'id_account' => 'accountId'
    );

    public function getCredit()
    {
        return $this['credit'];
    }

    public function getDebit()
    {

        return $this['debit'];
    }

    public function getMonth()
    {
        return $this['month'];
    }

    public function getBalance()
    {
        if (!$this->offsetExists('balance')) {
            return $this->getCredit() - $this->getDebit();
        }

        return $this['balance'];
    }

    public function toArray()
    {
        $data = $this->getArrayCopy();

        foreach ($data as $key => $value) {
            if( null === $value) {
                $data[$key] = 0;
            }
        }

        $data['balance'] = $this->getBalance();
        return $data;
    }


}