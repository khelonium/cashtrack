<?php
/**
 * @author Cosmin Dordea <cosmin.dordea@yahoo.com>
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */




namespace Report;


use Refactoring\Interval\IntervalInterface;
use Report\Account\Service;

class Expense
{
    /**
     * @var null|Account\Service
     */
    private $service = null;

    /**
     * @var null|\Refactoring\Interval\IntervalInterface
     */
    private $interval = null;

    public function __construct(Service $service, IntervalInterface $interval)
    {
        $this->service  = $service;
        $this->interval = $interval;
    }


    /**
     *
     */
    protected function getReport()
    {

    }


}