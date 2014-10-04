<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 * Date: 12/1/13
 */

namespace Finance\AccountValue;


use Database\AccountValue\AccountValueFactory;

trait AccountValueFactoryAwareTrait
{

    /**
     * @var accountValueFactory
     */
    private $accountValueFactory = null;

    public function setAccountValueFactory(AccountValueFactory $factory)
    {
        $this->accountValueFactory =  $factory;
    }

    /**
     * @return accountValueFactory
     * @throws \RuntimeException
     */
    protected function getAccountValueFactory()
    {
        if (null === $this->accountValueFactory) {
            throw new \RuntimeException("Account Value Factory not set");
        }

        return $this->accountValueFactory;
    }

}