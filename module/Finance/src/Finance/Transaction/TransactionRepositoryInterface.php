<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 11/10/14
 * Time: 00:34
 */
namespace Finance\Transaction;

use Refactoring\Db\Entity;
use Refactoring\Interval\IntervalInterface;

/**
 * Interface TransactionRepositoryInterface
 * Entity dependency must be removed
 * @package Finance\Transaction
 */
interface TransactionRepositoryInterface
{
    /**
     * Returns all entities
     * @return array
     */
    public function all();

    /**
     * @param $id
     * @return Transaction
     */
    public function get($id);

    /**
     * You are allowed to guess three times what this does
     * @param Entity $entity
     */
    public function add(Entity $entity);

    public function update(Entity $entity);

    /**
     * @param IntervalInterface $interval
     * @param null $account
     * @return array
     * @todo REFACTOR WITH specification
     */
    public function forInterval(IntervalInterface $interval);
}