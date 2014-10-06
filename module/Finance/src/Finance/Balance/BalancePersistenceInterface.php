<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 07/10/14
 * Time: 02:07
 */
namespace Finance\Balance;


/**
 * Knows how to persist a balance
 * @package Finance\Balance
 */
interface BalancePersistenceInterface
{
    /**
     * @param $balance
     */
    public function recordBalanceResult(OpenBalance $balance);

    /**
     * check if a month exists
     * @param \DateTime $day
     * @return bool
     */
    public function monthIsRecorded(\DateTime $day);

}