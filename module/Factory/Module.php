<?php

namespace Factory;

use Database\CashFlow\CashFlow;
use Database\Merchant\Repository as MerchantRepository;
use Finance\Account\Account;
use Finance\Merchant\Merchant as MerchantEntity;
use Finance\Transaction\Transaction as TransactionEntity;

use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;



class Module
{


    public function getServiceConfig()
    {
        return array(
            'initializers' => array(
                'database' => function ($service, $sm) {
                    if ($service instanceof AdapterAwareInterface) {
                        $service->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
                    }
                },
            ),

            'factories' => array(

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


                '\Reporter\CashFlow' => function ($sm) {
                    return new CashFlow();
                },



                '\Reporter\TimeMaster' => function () {
                    return new \Reporter\TimeMaster();
                },


            ),
        );
    }
}
