<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 12/10/14
 * Time: 20:30
 */
namespace Finance\CashFlow;

use Refactoring\Interval\IntervalInterface;

interface CashFlowInterface
{
    /**
     * @param IntervalInterface $interval
     * @return array of CashEntry
     */
    public function forInterval(IntervalInterface $interval);
}