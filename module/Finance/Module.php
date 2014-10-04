<?php

namespace Finance;

use Finance\Account\AccountFactory;
use Database\Account\AccountRepository;
use Finance\AccountValue\AccountValueFactory;


use Refactoring\Repository\GenericRepository;

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

            ),

            'factories' => array(

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
