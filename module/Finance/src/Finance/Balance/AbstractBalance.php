<?php
namespace Finance\Balance;


abstract class AbstractBalance
{


    /**
     * @var \stdClass
     */
    private $summary = null;

    public function getBalance()
    {
        return $this->getDebit() - $this->getCredit();
    }

    public function getCredit()
    {
        return $this->getSummary()->credit;
    }

    public function getDebit()
    {
        return $this->getSummary()->debit;
    }

    /**
     * Returns the list of account objects
     * @return mixed
     */
    abstract  public function accounts();

    protected function getSummary()
    {
        if ($this->summary) {
            return $this->summary;
        }

        $summary = new \stdClass();
        $summary->credit = 0;
        $summary->debit = 0;

        foreach ($this->accounts() as $account) {
            if ($account->getAccount()['type'] != 'buffer') {
                $summary->credit += $account->getCredit();
                $summary->debit += $account->getDebit();
            }

        }

        $this->summary = $summary;

        return $this->summary;

    }

}
