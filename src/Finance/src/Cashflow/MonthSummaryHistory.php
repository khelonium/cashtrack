<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/15/15
 * Time: 10:15 PM
 */

namespace Finance\Cashflow;

use Traversable;

class MonthSummaryHistory implements \IteratorAggregate
{
    private $entries;

    public function __construct(array $entries)
    {
        foreach ($entries as $summary) {
            $this->add($summary);
        }
    }

    private function add(MonthSummary $summary)
    {
        $this->entries [] = $summary;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->entries);
    }

    public function reduce(callable $callback, $carry)
    {
        return array_reduce($this->entries, $callback, $carry);
    }

    public function map(callable $callback)
    {
        return array_map($callback, $this->entries);
    }

    /**
     * @param callable $callback
     * @return MonthSummary
     */
    public function filter(callable $callback)
    {
        return new MonthSummary(array_filter($this->entries, $callback));
    }
}
