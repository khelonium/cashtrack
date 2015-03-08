<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logo
 * Date: 11/24/13
 * Time: 12:05 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Database\CashFlow;

use Finance\Cashflow\CashEntry;
use Finance\Reporter\CashFlowInterface;
use Refactoring\Time\Interval\IntervalInterface;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterAwareTrait;

class CashFlow implements AdapterAwareInterface, CashFlowInterface
{

    use AdapterAwareTrait;

    public function forInterval(IntervalInterface $interval)
    {

        return array_merge(
            $this->expensesFor($interval),
            $this->incomeFor($interval)
        );

    }

    private function fetchSql($sql)
    {
        /* @var \Zend\Db\Adapter\Adapter */
        $adapter   = $this->adapter;
        $statement = $adapter->query($sql);
        $results   =  $statement->execute();

        $out = array();

        $proto = new CashEntry();

        foreach ($results as $result) {
            $entry = clone $proto;

            $entry->name = $result['name'];
            $entry->accountId = $result['accountId'];
            $entry->amount    = $result['amount'];
            $entry->type      = $result['type'];
            $entry->month     = $result['month'];

            $out[] = $entry;
        }
        return $out;

    }

    /**
     * @param $start_day
     * @param $end_day
     * @return string
     */
    public function expensesFor(IntervalInterface $interval)
    {

        $start_day = $interval->getStart()->format('Y-m-d');
        $end_day = $interval->getEnd()->format('Y-m-d');

        $sql = "SELECT account.name,account.id as accountId, round(SUM( amount ),2) as amount, account.type,
            '$start_day' as 'month'
            FROM  `transaction` t
            LEFT JOIN account ON account.id = t.to_account
            WHERE t.transaction_date >=  '$start_day'
            AND t.transaction_date <=  '$end_day'
            AND account.type='expense'

            GROUP BY account.name";

        return $this->fetchSql($sql);
    }

    /**
     * @param $start_day
     * @param $end_day
     * @return string
     */
    public function incomeFor(IntervalInterface $interval)
    {

        $start_day = $interval->getStart()->format('Y-m-d');
        $end_day = $interval->getEnd()->format('Y-m-d');

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

        return $this->fetchSql($income_sql);
    }
}