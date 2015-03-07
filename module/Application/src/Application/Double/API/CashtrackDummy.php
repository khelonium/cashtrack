<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/7/15
 * Time: 9:21 PM
 */

namespace Application\Double\API;


use Finance\Reporter\CashFlowInterface;
use Refactoring\Time\Interval\IntervalInterface;

class CashtrackDummy implements CashFlowInterface
{
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
        // TODO: Implement expensesFor() method.
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