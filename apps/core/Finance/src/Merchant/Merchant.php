<?php
/**
 * @author Cosmin Dordea <cosmin.dordea@yahoo.com>
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */

namespace Finance\Merchant;


/**
 * Class Entity
 * @property accountId
 * @property id
 * @property identifier
 * @property name
 * @package Finance\Merchant
 */
class Merchant extends \Library\Entity
{
    protected $map =  array (
        'id_account' => 'accountId'
    );

}