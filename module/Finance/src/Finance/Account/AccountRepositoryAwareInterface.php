<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 * Date: 12/1/13
 */

namespace Finance\Account;


interface AccountRepositoryAwareInterface
{
    public function setAccountRepository(AccountRepository $repository);
}