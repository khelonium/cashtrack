<?php
namespace Finance\Reporter;

use Library\Collection;
use Refactoring\Time\Interval\IntervalInterface;

interface CashFlowInterface
{
    /**
     * @param IntervalInterface $interval
     * @return Collection
     */
    public function forInterval(IntervalInterface $interval);

    /**
     * @param IntervalInterface $interval
     * @return Collection
     */
    public function expensesFor(IntervalInterface $interval);

    /**
     * @param IntervalInterface $interval
     * @return Collection
     */
    public function incomeFor(IntervalInterface $interval);
}