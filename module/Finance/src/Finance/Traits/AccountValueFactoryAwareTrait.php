<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 * Date: 12/1/13
 */

namespace Finance\Traits;



use Finance\AccountValue\AccountValueFactoryInterface;

trait AccountValueFactoryAwareTrait
{

    /**
     * @var AccountValueFactoryInterface
     */
    private $accountValueFactory = null;

    public function setAccountValueFactory(AccountValueFactoryInterface $factory)
    {
        $this->accountValueFactory =  $factory;
    }

    /**
     * @return AccountValueFactoryInterface
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