<?php

namespace Database;

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


}
