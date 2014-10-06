<?php

namespace Database;

use Database\AccountValue\AccountValueFactory;
use Database\Balance\Repository as BalanceRepository;
use Database\Merchant\Repository as MerchantRepository;
use Finance\Account\Account;
use Finance\Account\AccountFactoryAwareInterface;
use Database\Account\AccountRepositoryAwareInterface;
use Finance\AccountValue\AccountValueFactoryAwareInterface;
use Database\Balance\BalanceRepositoryAwareInterface;
use Database\Balance\Balance;
use Finance\Merchant\Merchant as MerchantEntity;
use Finance\Transaction\Transaction as TransactionEntity;


use Finance\Transaction\TransactionRepositoryAwareInterface;
use Refactoring\Repository\GenericRepository;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'Reporter' => __DIR__ . '/src/Reporter',

                ),
            ),

        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'initializers' => array(
                'database' => function ($service, $sm) {
                    if ($service instanceof AdapterAwareInterface) {
                        $service->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
                    }
                },

                'accountRepository' => function ($service, $sm) {
                    if ($service instanceof AccountRepositoryAwareInterface) {
                        $service->setAccountRepository($sm->get('Database\Account\Repository'));
                    }
                },

                'accountValueFactory' => function ($service, $sm) {
                    if ($service instanceof AccountValueFactoryAwareInterface) {
                        $service->setAccountValueFactory($sm->get('Database\AccountValue\AccountValueFactory'));
                    }
                },

                'transactionRepository' => function ($service, $sm) {

                    if ($service instanceof TransactionRepositoryAwareInterface) {
                        $service->setTransactionRepository($sm->get('Database\Transaction\Repository'));
                    }
                },

                'balanceRepository' => function ($service, $sm) {

                    if ($service instanceof BalanceRepositoryAwareInterface) {
                            $service->setBalanceRepository($sm->get('\Database\Balance\Repository'));
                    }
                },
            ),

            'factories' => array(

                '\Database\AccountValue\AccountValueFactory' => function ($sm) {
                    $factory = new AccountValueFactory();
                    $factory->setAccountFactory($sm->get('Finance\Account\AccountFactory'));
                    return $factory;
                },


                '\Database\Transaction\Repository' => function ($sm) {
                    return new \Database\Transaction\Repository();
                },

                '\Database\Merchant\Repository' => function ($sm) {
                    return new MerchantRepository();
                },

                '\Database\Account\Repository' => function ($sm) {
                    return new \Database\Account\AccountRepository();
                },

                '\Finance\Dao\AccountGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Account());
                    return new TableGateway('account', $dbAdapter, null, $resultSetPrototype);
                },
                '\Finance\Dao\MerchantGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new MerchantEntity());
                    return new TableGateway('merchant', $dbAdapter, null, $resultSetPrototype);
                },

                '\Finance\Dao\TransactionGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new TransactionEntity());
                    return new TableGateway('transaction', $dbAdapter, null, $resultSetPrototype);
                },

                '\Database\Dao\BalanceGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Balance());
                    return new TableGateway('balance', $dbAdapter, null, $resultSetPrototype);
                },

                '\Database\Balance\Repository' => function ($sm) {
                    return new BalanceRepository($sm->get('\Database\Dao\BalanceGateway'));
                },

            ),
        );
    }
}
