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
use Finance\Balance\History\BalanceRepositoryAwareInterface;
use Finance\Balance\History\History;
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
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
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

                'transactionRepository' => function ($service, $sm) {

                    if ($service instanceof TransactionRepositoryAwareInterface) {
                        $service->setTransactionRepository($sm->get('Finance\Transaction\Repository'));
                    }
                },

                'balanceRepository' => function ($service, $sm) {

                    if ($service instanceof BalanceRepositoryAwareInterface) {
                            $service->setBalanceRepository($sm->get('\Finance\Balance\History\Repository'));
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

                '\Finance\Dao\BalanceGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new History());
                    return new TableGateway('balance', $dbAdapter, null, $resultSetPrototype);
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

                '\Finance\AccountValue\AccountValueFactory' => function ($sm) {
                    return new AccountValueFactory();
                },
                '\Finance\Account\AccountFactory' => function ($sm) {
                    return new AccountFactory();
                },

                '\Finance\Account\AccountRepository' => function ($sm) {
                    return new AccountRepository();
                },

                '\Finance\Balance\History\Repository' => function ($sm) {
                    return new GenericRepository($sm->get('\Finance\Dao\BalanceGateway'));
                },

                '\Reporter\CashFlow' => function ($sm) {
                    return new \Reporter\CashFlow();
                },

                '\Reporter\Overview' => function () {
                    return new \Reporter\Overview();
                },

            ),
        );
    }
}
