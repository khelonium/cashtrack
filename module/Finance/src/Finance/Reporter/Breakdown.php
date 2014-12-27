<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 12/25/14
 * Time: 10:52 PM
 */

namespace Finance\Reporter;


use Refactoring\Time\Interval;

class Breakdown implements BreakdownInterface
{
    /**
     * @var CashFlowInterface
     */
    private $cashflow = null;

    public function __construct(CashFlowInterface $cashflow)
    {
        $this->cashflow = $cashflow;
    }


    public function week($year, $week)
    {
        return $this->cashflow->getExpenses($this->makeWeekInterval($year, $week));
    }

    public function month($year, $month)
    {
        return $this->cashflow->getExpenses(new Interval\SpecificMonth(new \DateTime("$year-$month-01")));
    }

    /**
     * @param $year
     * @param $week
     * @return Interval
     */
    protected function makeWeekInterval($year, $week)
    {
        $week_start = new \DateTime();
        $week_start->setISODate($year, $week);

        $week_end = clone $week_start;
        $interval = new \DateInterval('P6D');
        $week_end->add($interval);

        return new Interval($week_start, $week_end);
    }

}