<?php
namespace Library;

class Collection implements \IteratorAggregate, \Countable
{
    private $entries = [];

    public function __construct(array $entries)
    {
        foreach ($entries as $summary) {
            $this->entries [] = $summary;
        }
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
        if (($index+1) > count($this->entries)) {
            throw new \OutOfBoundsException();
        }
        return $this->entries[$index+1];
    }

    /**
     * @param callable $callback
     * @return Collection
     */
    public function filter(callable $callback)
    {
        return new Collection(array_filter($this->entries, $callback));
    }

    /**
     * @param callable $callback
     * @return Collection
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

