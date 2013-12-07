<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */
namespace Finance\Account;

/**
 * Class HasAccountFactoryTrait
 * Implemented for  quickly injecting the factory where required
 * @package Finance\Account
 */
trait AccountFactoryAwareTrait
{
    /**
     * @var AccountFactory
     */
    private $accountFactory = null;

    public function setAccountFactory(AccountFactory $factory)
    {
        $this->accountFactory = $factory;
    }


    protected function getAccountFactory()
    {
        if (null === $this->accountFactory) {
           throw new \RuntimeException('Account Factory not configured');

        }
        return $this->accountFactory;
    }
}