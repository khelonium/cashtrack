<?php
namespace Watch;

use Finance\Reporter\CashFlowInterface;
use Refactoring\Time\Interval;
use Refactoring\Time\Interval\ThisMonth;

class Overflow
{
    private $interval;
    const WARNING_LIMIT = 0.75;

    /**
     * @var CashFlowInterface
     */
    private $cashflow = null;

    /**
     * @param CashFlowInterface $cashflow
     * @param Interval $interval
     */
    public function __construct(CashFlowInterface $cashflow, $interval = null)
    {
        $this->interval = $interval or $this->interval = new ThisMonth();
        $this->cashflow = $cashflow;
    }


    public function isAbove($limit)
    {

        $collection = $this->cashflow->expensesFor($this->interval);

        if ($collection->isEmpty()) {
            return false;
        }

        $total = $collection->map(
            function ($el) {
                return $el->amount;
            }
        );

        return array_sum($total->toArray()) > $limit;
    }

    public function isAlmostAbove($limit)
    {
        return $this->isAbove(self::WARNING_LIMIT * $limit);
    }
}