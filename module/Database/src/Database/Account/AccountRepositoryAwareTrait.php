<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 * Date: 12/1/13
 */

namespace Database\Account;


use Database\Account\AccountRepository;

trait AccountRepositoryAwareTrait
{

    /**
     * @var AccountRepository
     */
    private $accountRepository = null;

    public function setAccountRepository(AccountRepository $repository)
    {
        $this->accountRepository = $repository;
    }

    final protected function getAccountRepository()
    {
        if (null === $this->accountRepository) {
            throw new \RuntimeException('Account Repository not set');
        }
        return $this->accountRepository;
    }

}