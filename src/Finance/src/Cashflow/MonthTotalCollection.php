<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/15/15
 * Time: 10:15 PM
 */

namespace Finance\Cashflow;


class MonthTotalCollection implements \IteratorAggregate, \Countable
{
    private $entries = [];

    public function __construct(array $entries)
    {
        foreach ($entries as $summary) {
            $this->add($summary);
        }
    }

    private function add(MonthTotal $summary)
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

    public function first()
    {
        if ($this->isEmpty()) {
            throw new \OutOfBoundsException();
        }

        return $this->entries[0];
    }

    public function last()
    {
        if ($this->isEmpty()) {
            throw new \OutOfBoundsException();
        }

        return $this->entries[count($this->entries) -1];

    }

    public function nth($index)
    {
        if ($index > count($this->entries)) {
            throw new \OutOfBoundsException();
        }
        return $this->entries[$index];
    }

    /**
     * @param callable $callback
     * @return MonthTotalCollection
     */
    public function filter(callable $callback)
    {
        return new MonthTotalCollection(array_filter($this->entries, $callback));
    }

    /**
     * @param callable $callback
     * @return MonthTotalCollection
     */
    public function sort(callable $callback)
    {
        $theClone = clone $this;
        usort(
            $theClone->entries,
            $callback
        );

        return $theClone;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->entries);
    }

    /**
     * @return bool
     */
    protected function isEmpty()
    {
        return 0 == count($this->entries);
    }


}
