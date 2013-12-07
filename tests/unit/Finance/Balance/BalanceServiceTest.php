<?php
namespace FinanceTest\Balance;

use Codeception\Util\Stub;
use Finance\Balance\AbstractBalance;
use Finance\Balance\BalanceService;
use Refactoring\Interval\SpecificMonth;
use Zend\View\Exception\DomainException;

/**
 * Class BalanceServiceTest
 * @package FinanceTest\Balance
 * @group balance
 */
class BalanceServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \CodeGuy
     */
    protected $codeGuy;



    public function testGetBalanceFunctional()
    {
        $balance = \ApplicationTest\Bootstrap::getServiceManager()->get('\Finance\Balance\BalanceService');

        $open = $balance->getBalance(new SpecificMonth(new \DateTime('2013-06-01')));

        $this->assertEquals($open->getMonth()->getStart()->format('Y-m-d'), '2013-06-01');
        $this->assertEquals($open->getMonth()->getEnd()->format('Y-m-d'), '2013-06-30');

        $this->assertEquals(4647, $open->getCredit());
        $this->assertEquals(143707, $open->getDebit());

    }


    public function testBalanceReturnedByCloseMonth()
    {
        $balance = \ApplicationTest\Bootstrap::getServiceManager()->get('\Finance\Balance\BalanceService');



        $closed = $balance->closeMonth(new SpecificMonth(new \DateTime('2013-06-01')));

        $this->codeGuy->cantSeeInDatabase(
            'transaction',
            array('from_account' => 55, 'transaction_date' => '2013-06-30'),
            array('from_account' => 55, 'transaction_date' => '2013-06-01')
        );

        $this->assertTrue($closed instanceof AbstractBalance);

        $this->assertEquals($closed->getMonth()->getStart()->format('Y-m-d'), '2013-06-01');
        $this->assertEquals($closed->getMonth()->getEnd()->format('Y-m-d'), '2013-06-30');

        $this->assertEquals(4647, $closed->getCredit());
        $this->assertEquals(143707, $closed->getDebit());

    }


    // tests
    public function testCloseMonthWhenMonthIsNotInDatabase()
    {

        $returns_date = function () {
            return new \DateTime(('2090-12-12'));
        };


        $specic_month = Stub::make(
            '\Refactoring\Interval\SpecificMonth',
            array(
                'getStart' => $returns_date,
                'getEnd' => $returns_date,
            )
        );

        $service = new BalanceService();
        $service->setTransactionRepository($this->getTransactionRepository());
        $service->setAccountValueFactory($this->getAccountValueFactory());

        try {
            $service->closeMonth($specic_month);
            $this->fail("Expected exception not called");
        } catch (\DomainException $e) {

            $this->assertEquals("There are no transactions in this month 2090-12-12", $e->getMessage());
        }
    }

    public function testCanNotCloseMonthWithNegativeBuffers()
    {
        $this->codeGuy->dontSeeInDatabase(
            'transaction',
            array('from_account' => 55, 'to_account' => 52, 'amount' => 61927)
        );

        //fixme not really unit test, more like functional
        //todo move functional in functional test suite
        /** @var \Finance\Balance\BalanceService $balance */
        $balance = \ApplicationTest\Bootstrap::getServiceManager()->get('\Finance\Balance\BalanceService');
        try {
            $balance->closeMonth(new SpecificMonth(new \DateTime('2013-10-01')));
        } catch (\DomainException $e) {
            $this->assertEquals("This month can not be closed", $e->getMessage());

        }


    }

    public function testCanNotCloseMonthTwice()
    {
        $this->testCanCloseMonth();
        /** @var \Finance\Balance\BalanceService $balance */
        $balance = \ApplicationTest\Bootstrap::getServiceManager()->get('\Finance\Balance\BalanceService');

        try {
            $balance->closeMonth(new SpecificMonth(new \DateTime('2013-06-01')));
            $this->fail("Expected exception not raised");
        } catch (\DomainException $e) {
            $this->assertEquals("Month 2013-06-01 already closed", $e->getMessage());
        }

    }


    public function testCanCloseMonth()
    {
        $this->codeGuy->dontSeeInDatabase(
            'transaction',
            array('from_account' => 55, 'to_account' => 47, 'amount' => 353, 'transaction_date', '2013-07-01')
        );

        //todo move functional in functional test suite
        /** @var \Finance\Balance\BalanceService $balance */
        $balance = \ApplicationTest\Bootstrap::getServiceManager()->get('\Finance\Balance\BalanceService');
        try {
            $closed = $balance->closeMonth(new SpecificMonth(new \DateTime('2013-06-01')));
        } catch (\DomainException $e) {
            $this->assertEquals("This month can not be closed", $e->getMessage());

        }


        $account_values = [
            47 => 139060,
        ];

        foreach ($account_values as $id => $value) {
            $this->codeGuy->seeInDatabase(
                'transaction',
                array('from_account' => 55, 'to_account' => $id, 'amount' => $value)
            );
        }

        $this->codeGuy->seeInDatabase(
            'balance',
            array(
                'month'   => '2013-06-01',
                'credit'  => $closed->getCredit(),
                'debit'   => $closed->getDebit(),
                'balance' => $closed->getBalance()
            )
        );

    }


    public function getAccountValueFactory()
    {
        $methods = [];
        return Stub::make('\Finance\AccountValue\AccountValueFactory', $methods);
    }

    public function getTransactionRepository()
    {

        $methods = [
            'forInterval' => function () {
                    return [];
            },
        ];

        return Stub::make('\Finance\Transaction\Repository', $methods);
    }

    public function testThatDbIsRestored()
    {

    }
}
