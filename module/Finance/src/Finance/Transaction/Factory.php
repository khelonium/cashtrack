<?php

namespace Finance\Transaction;
use Reporter\Transaction\Entity;

/**
 * Class Factory
 * Used to create transactions from various criterias
 */
class Factory
{
    /**
     * Array which contains key value pairs in the form
     *
     * @param $data
     * @return Entity
     */
    public function fromArray($data)
    {
        return new Entity($data);
    }
}