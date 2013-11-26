<?php
namespace ReportTest;

use Codeception\Command\Bootstrap;
use Report\CashFlow;

class CashFlowTest extends \Codeception\TestCase\Test
{


    // tests
    public function testMe()
    {
//        $cash = new CashFlow();
//        $CashFlow = new CashFl¢ow();
        $this->codeGuy->seeInDatabase('transaction',array('amount' => 2504));

//
//        $sm = \Bootstrap::getServiceManager();
//        $cashflow = $sm->get('\Finance\Balance\BalanceService');
////        $adapter =$sm->get('\Zend\Db\Adapter\Adapter');
    }

}