<?php

namespace Database;

use Finance\Account\Account;
use Finance\Account\AccountFactoryAwareInterface;
use Finance\Account\AccountRepositoryAwareInterface;
use Finance\AccountValue\AccountValueFactoryAwareInterface;
use Finance\Balance\History\BalanceRepositoryAwareInterface;
use Finance\Balance\History\History;
use Finance\Merchant\Merchant as MerchantEntity;
use Finance\Transaction\Transaction as TransactionEntity;


use Finance\Transaction\TransactionRepositoryAwareInterface;
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
                        $service->setAccountRepository($sm->get('Finance\Account\AccountRepository'));
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
                '\Finance\Account\Repository' => function ($sm) {
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

                '\Finance\Dao\BalanceGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new History());
                    return new TableGateway('balance', $dbAdapter, null, $resultSetPrototype);
                },

            ),
        );
    }
}
