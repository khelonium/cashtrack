<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logo
 * Date: 11/24/13
 * Time: 12:05 AM
 * To change this template use File | Settings | File Templates.
 */

namespace  Reporter;

use Refactoring\Interval\IntervalInterface;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class CashFlow  implements  AdapterAwareInterface
{

    use AdapterAwareTrait;

    public function forInterval(IntervalInterface $interval)
    {


        $start_day = $interval->getStart()->format('Y-m-d');
        $end_day   = $interval->getEnd()->format('Y-m-d');

        $sql = "SELECT account.name,account.id as accountId, round(SUM( amount ),2) as amount, account.type,
            '$start_day' as 'month'
            FROM  `transaction` t
            LEFT JOIN account ON account.id = t.to_account
            WHERE t.transaction_date >=  '$start_day'
            AND t.transaction_date <=  '$end_day'
            GROUP BY account.name";


        $income_sql = "
            SELECT
              account.name,account.id as accountId, round(SUM( amount ),2) as amount, account.type,
              '$start_day' as 'month'
            FROM  `transaction` t
            LEFT JOIN account ON account.id = t.from_account
            WHERE t.transaction_date >=  '$start_day'
            AND t.transaction_date <=  '$end_day'
            AND account.type='income'
            GROUP BY account.name";
        return array_merge($this->fetchSql($sql), $this->fetchSql($income_sql));

        return $this->fetchSql($sql);

    }

    private function fetchSql($sql)
    {
        /* @var \Zend\Db\Adapter\Adapter */
        $adapter   = $this->adapter;
        $statement = $adapter->query($sql);
        $results   =  $statement->execute();

        $out = array();

        foreach($results as $result) {
            $out[] = $result;
        }
        return $out;

    }
}