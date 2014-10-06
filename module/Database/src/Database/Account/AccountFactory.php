<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Database\Account;

use Finance\Account\Account;

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