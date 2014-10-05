<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 05/10/14
 * Time: 12:03
 */
namespace Finance\AccountValue;

use Refactoring\Interval\IntervalInterface;
use Finance\AccountValue\AccountValue;


/**
 * Class AccountFactory
 * not sure if this is really a factory
 * @package Finance\AccountValue
 */
interface AccountValueFactoryInterface
{
    /**
     * Returns the balance for a certain account
     * @param int $account id
     * @param IntervalInterface $interval
     * @return AccountValue
     */
    public function get($account, IntervalInterface $interval);

    /**
     * Gets the value for all accounts for a specific interval
     * @param IntervalInterface $interval
     */
    public function forAllAccounts(IntervalInterface $interval);

    /**
     * @param $accountIds
     * @param IntervalInterface $interval
     * @return array
     */
    public function getList($accountIds, IntervalInterface $interval);
}