<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 12/10/14
 * Time: 20:30
 */
namespace Finance\Reporter;

use Refactoring\Time\Interval\IntervalInterface;

interface CashFlowInterface
{
    /**
     * @param IntervalInterface $interval
     * @return array of CashEntry
     */
    public function forInterval(IntervalInterface $interval);

    /**
     * @param IntervalInterface $interval
     * @return string
     */
    public function getExpenses(IntervalInterface $interval);

    /**
     * @param IntervalInterface $interval
     * @return string
     */
    public function getIncomes(IntervalInterface $interval);
}