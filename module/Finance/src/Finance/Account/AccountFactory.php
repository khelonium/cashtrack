<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Account;

/**
 * Class Factory
 * @package Finance\Account
 */
class AccountFactory
{


    /**
     * @param array $dbData
     * @return Account
     */
    public function fromDatabaseArray(array $dbData)
    {
        $account = new Account();
        $account->exchangeArray($dbData);
        return $account;
    }
}