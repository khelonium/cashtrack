<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 12/25/14
 * Time: 10:55 PM
 */

namespace unit\Finance\Reporter;


use Finance\Reporter\Breakdown;
use Finance\Reporter\CashFlowInterface;
use Refactoring\Interval\IntervalInterface;

class BreakdownTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var CashflowDouble
     */
    private $cashflow;

    /**
     * @var Breakdown
     */
    private $reporter;

    /**
     * @before
     */
    function before()
    {
        $this->cashflow = new CashFlowDouble();
        $this->reporter = new Breakdown($this->cashflow);
    }

    /**
     * @test
     */
    function weekWillSendAWeekIntervalToCashflow()
    {

        $this->reporter->week(2014,1);
        $this->assertTrue($this->cashflow->getExpensesWasCalled());
        $this->assertEquals('2013-12-30', $this->cashflow->getStart());
        $this->assertEquals('2014-01-05', $this->cashflow->getEnd());
    }

    /**
     * @test
     */
    function itFormsCorrectMonthInterval()
    {
        $this->reporter->month(2014,2);
        $this->assertTrue($this->cashflow->getExpensesWasCalled());
        $this->assertEquals('2014-02-01', $this->cashflow->getStart());
        $this->assertEquals('2014-02-28', $this->cashflow->getEnd());
    }


    /**
     * @test
     */
    function breakDownWillReturnSameAnswerAsCashflow()
    {

        $toReturn = new \stdClass();

        $this->cashflow->willReturn($toReturn);

        $reporter = new Breakdown($this->cashflow);

        $response = $reporter->week(2014,1);;

        $this->assertTrue($response === $toReturn);

    }
}

class CashflowDouble implements CashFlowInterface
{
    /**
     * @var IntervalInterface
     */
    private $interval;

    private $toReturn;

    private $expenseWasCalled = false;

    /**
     * @param IntervalInterface $interval
     * @return array of CashEntry
     */
    public function forInterval(IntervalInterface $interval)
    {
        $this->interval = $interval;
        return $this->toReturn;

    }


    public function getStart()
    {
        return $this->interval->getStart()->format('Y-m-d');
    }

    public function getEnd()
    {
        return $this->interval->getEnd()->format('Y-m-d');
    }

    public function wasCalled()
    {
        if (!$this->interval) {
            throw new \Exception("Cashflow was not called");
        }
    }

    public function willReturn($toReturn)
    {
        $this->toReturn = $toReturn;
    }

    public function getExpenses(IntervalInterface $interval)
    {
        $this->interval  = $interval;
        $this->expenseWasCalled = true;
        return $this->toReturn;
    }

    public function getIncomes(IntervalInterface $interval)
    {
        // TODO: Implement getIncomes() method.
    }

    public function getExpensesWasCalled()
    {
        return $this->expenseWasCalled;
    }
}