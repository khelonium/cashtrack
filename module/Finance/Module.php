<?php

namespace Finance;

use Database\Account\AccountFactory;



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

                '\Finance\Balance\BalanceService' => function ($sm) {
                    return new \Finance\Balance\Balancer($sm->get('\Database\Balance\Repository'));
                },

                '\Finance\Account\AccountFactory' => function ($sm) {
                    return new AccountFactory();
                },


                '\Reporter\Overview' => function () {
                    return new \Reporter\Overview();
                },


                '\Reporter\TimeMaster' => function () {
                    return new \Reporter\TimeMaster();
                },
            ),
        );
    }
}
