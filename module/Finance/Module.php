<?php

namespace Finance;

use Finance\Account\Account as ExpenseEntity;
use Finance\Account\Account;
use Finance\Account\AccountFactory;
use Finance\Account\AccountFactoryAwareInterface;
use Finance\Account\AccountRepository;
use Finance\Account\AccountRepositoryAwareInterface;
use Finance\AccountValue\AccountValueFactory;
use Finance\AccountValue\AccountValueFactoryAwareInterface;
use Finance\Merchant\Merchant as MerchantEntity;
use Finance\Transaction\Transaction as TransactionEntity;


use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'Report' => __DIR__ . '/src/Report',

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
                'db' => function($service, $sm) {
                    if ($service instanceof AdapterAwareInterface) {
                        $service->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
                    }

                },
                'accountFactory' => function ($service, $sm) {
                    if ($service instanceof AccountFactoryAwareInterface) {

                        $service->setAccountFactory($sm->get('Finance\Account\AccountFactory'));
                    }
                },
                'accountValueFactory' => function ($service, $sm) {

                    if ($service instanceof AccountValueFactoryAwareInterface) {
                        $service->setAccountValueFactory($sm->get('Finance\AccountValue\AccountValueFactory'));
                    }
                },

                'accountRepository' => function ($service, $sm) {

                    if ($service instanceof AccountRepositoryAwareInterface) {
                        $service->setAccountRepository($sm->get('Finance\Account\AccountRepository'));
                    }
                },


            ),
            'factories' => array(
                '\Finance\Dao\AccountGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new ExpenseEntity());
                    return new TableGateway('account', $dbAdapter, null, $resultSetPrototype);
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

                '\Finance\Account\Repository' => function ($sm) {
                    return new \Finance\Account\Repository();
                },

                '\Finance\Merchant\Repository' => function ($sm) {
                    return new \Finance\Merchant\Repository();
                },

                '\Finance\Transaction\Repository' => function ($sm) {
                    return new \Finance\Transaction\Repository();
                },

                '\Finance\Balance\BalanceService' => function ($sm) {
                    return new \Finance\Balance\BalanceService($sm->get('Zend\Db\Adapter\Adapter'));
                },

                '\Report\CashFlow' => function ($sm) {
                    return new \Report\CashFlow();
                },

                '\Finance\AccountValue\AccountValueFactory' => function($sm) {
                    return new AccountValueFactory();
                },
                '\Finance\Account\AccountFactory' => function ($sm) {
                    return new AccountFactory();
                },

                '\Finance\Account\AccountRepository' => function ($sm) {
                    return new AccountRepository();
                },

            ),
        );
    }


}