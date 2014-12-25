<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 12/25/14
 * Time: 7:10 PM
 */
namespace Application\Double\API;

use Finance\Reporter\BreakdownInterface;

class WeekService implements BreakdownInterface
{
    private $year;
    private $unit;
    private $monthWasCalled = false;
    private $weekWasCalled = false;

    public function week($year, $week)
    {

        $this->weekWasCalled = true;
        $this->year = $year;
        $this->unit = $week;
        return  [
            [
                'name' => 'Category 1',
                'amount' => 100,
            ],
            [
                'name' => 'Category 2',
                'amount' => 50,
            ]

        ];
    }

    public function month($year, $month)
    {
        $this->monthWasCalled = true;
        $this->year = $year;
        $this->unit = $month;
    }

    private function wasCalledWith($year, $unit)
    {

        if ($this->year != $year) {
            throw new \Exception("Not the year we are looking for: $year versus ".$this->year);
        }
        if ($this->unit != $unit) {
            throw new \Exception("Not the unit we are looking for");

            return false;
        }

        return true;
    }


    public function monthWasCalledWith($year, $unit)
    {
        if (!$this->monthWasCalled ){
            throw new \Exception("Month was not called");
        }
        return $this->wasCalledWith($year, $unit);
    }

    public function weekWasCalledWith($year, $unit)
    {
        if (!$this->weekWasCalled ){
            throw new \Exception("Week was not called");
        }
        return $this->wasCalledWith($year, $unit);
    }
}