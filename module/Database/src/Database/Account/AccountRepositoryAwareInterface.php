<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 * Date: 12/1/13
 */

namespace Database\Account;


use Database\Account\AccountRepository;

interface AccountRepositoryAwareInterface
{
    public function setAccountRepository(AccountRepository $repository);
}