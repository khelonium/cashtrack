<?php
namespace Overflow;

use Database\Transaction\Repository;
use Finance\Cashflow\AccountTotal;
use Finance\Reporter\CashFlowInterface;
use Refactoring\Time\Interval\IntervalInterface;
use Refactoring\Time\Interval\ThisYear;
use Watch\Overflow;

class OverflowTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    */
    public function withNoEntriesThereIsNoOverflow()
    {
        $overflow = new Overflow(new CashDouble());
        $this->assertFalse($overflow->isAbove(10));
    }

    /**
     * @test
     */
    public function weUseACashflowObject()
    {

        $double = new CashDouble();

        $overflow = new Overflow($double);

        $overflow->isAbove(10);

        $this->assertTrue($double->expenseCalled);
    }

    /**
     * @test
     */
    public function isAbove()
    {

        $double = new CashDouble();

        $overflow = new Overflow($double);

        $expense = new AccountTotal();
        $expense->id       = 1;
        $expense->type    = 'expense';
        $expense->amount =  11;


        $double->expenses[] = $expense;

        $this->assertTrue($overflow->isAbove(10));

        $double->expenses[] = $expense;

        $this->assertTrue($overflow->isAbove(20));

    }

    /**
     * @test
     */
    public function weCanPassAStrategy()
    {

        $year = new ThisYear();
        $double = new CashDouble();
        $overflow = new Overflow($double, $year);

        $overflow->isAbove(10);

        $this->assertSame($year, $double->strategy);
    }

}

class CashDouble implements CashFlowInterface
{
    public $expenses = [];

    public $expenseCalled = false;

    public $strategy;

    /**
     * @param IntervalInterface $interval
     * @return array of CashEntry
     */
    public function forInterval(IntervalInterface $interval)
    {
        // TODO: Implement forInterval() method.
    }

    /**
     * @param IntervalInterface $interval
     * @return string
     */
    public function expensesFor(IntervalInterface $interval)
    {
        $this->strategy = $interval;

        $this->expenseCalled = true;
        return $this->expenses;
    }

    /**
     * @param IntervalInterface $interval
     * @return string
     */
    public function incomeFor(IntervalInterface $interval)
    {
        // TODO: Implement incomeFor() method.
    }

}