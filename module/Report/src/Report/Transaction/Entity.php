<?php
/**
 * @author Cosmin Dordea <cosmin.dordea@yahoo.com>
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */




namespace Report\Transaction;



use Refactoring\Db\Entity as AbstractEntity;

/**
 *  Generic class that can be used to map entries in reports
 * @package Report\Expense
 */
class Entity extends AbstractEntity
{

    protected $map = array(
        'total' => 'amount',
    );

    public function getAmount()
    {
        return $this['amount'];
    }

    public function getName()
    {
        return $this['name'];
    }




}