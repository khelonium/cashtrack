<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Balance;


class SubsetBalance extends AbstractBalance
{

    /**
     * @var null|ArrayObject|Array
     */
    private $accounts = null;

    public function __construct(OpenBalance $balance, $accountType)
    {
        $this->accounts = new \ArrayObject();
        foreach ($balance->accounts() as $account) {
            if($account->getAccount()['type'] == $accountType) {
                $this->accounts[] = $account;
            }
        }

    }

    /**
     * Returns the list of account objects
     * @return mixed
     */
    public function accounts()
    {
        return $this->accounts;
    }

}