<?php

namespace FinanceTest\Balance;

use Finance\Balance\Balance;
use Finance\Balance\BalanceService;

class BalanceServiceTest extends \PHPUnit_Framework_TestCase
{

    private $mockAdapter = null;

    public function setUp()
    {
        // mock the adapter, driver, and parts
        $mockResult = $this->getMock('Zend\Db\Adapter\Driver\ResultInterface');
        $mockStatement = $this->getMock('Zend\Db\Adapter\Driver\StatementInterface');
        $mockStatement->expects($this->any())->method('execute')->will($this->returnValue($mockResult));
        $mockConnection = $this->getMock('Zend\Db\Adapter\Driver\ConnectionInterface');
        $mockDriver = $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
        $mockDriver->expects($this->any())->method('createStatement')->will($this->returnValue($mockStatement));
        $mockDriver->expects($this->any())->method('getConnection')->will($this->returnValue($mockConnection));

        // setup mock adapter
        $this->mockAdapter = $this->getMock('Zend\Db\Adapter\Adapter', null, array($mockDriver));
    }

    public function testGet()
    {

        $intervalMock =    $mockResult = $this->getMock('Refactoring\Interval\IntervalInterface');
        $intervalMock->expects($this->any())
                    ->method('getStart')
                    ->will($this->returnValue(new \DateTime()));

        $intervalMock->expects($this->any())
            ->method('getEnd')
            ->will($this->returnValue(new \DateTime()));

        $service      = new BalanceService($this->mockAdapter);
        $balance = $service->get(1, $intervalMock);

        $this->assertTrue($balance instanceof Balance);
    }


}
