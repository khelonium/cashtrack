<?php
/**
 * @author Cosmin Dordea <cosmin.dordea@yahoo.com>
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */

namespace Finance\Transaction;


/**
 * Class Entity
 * @property accountId
 * @property id
 * @package Finance\Merchant
 */
class Transaction extends \Database\Repository\Entity
{
    protected $map =  array (
        'from_account'     => 'fromAccount',
        'to_account'       => 'toAccount',
        'transaction_date' => 'date',
        'transaction_ref'  => 'reference'
    );

}