<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/16/15
 * Time: 9:15 PM
 */

namespace Database\Account;


use Finance\Account\Account;
use Finance\Account\BalanceInterface;
use Finance\Cashflow\MonthTotal;
use Finance\Cashflow\MonthTotalCollection;
use Refactoring\Time\Interval;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Sql;

class Balance implements BalanceInterface, AdapterAwareInterface
{
    use AdapterAwareTrait;

    /**
     * @var Account
     */
    private $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @param Interval $interval
     * @return MonthTotalCollection
     */
    public function totalFor(Interval $interval)
    {

        $result =  $this->getSql()->prepareStatementForSqlObject($this->buildSelect($interval))->execute();

        $out = [];

        foreach ($result as $entry) {
            $out[] = $this->buildEntry($entry);
        }

        return new MonthTotalCollection($out);
    }


    /**
     * @return Sql
     */
    protected function getSql()
    {
        $sql = new Sql($this->adapter);
        return $sql;
    }

    /**
     * @param $entry
     * @return MonthTotal
     */
    protected function buildEntry($entry)
    {
        $total = new MonthTotal();
        $total->amount = $entry['amount'];
        $total->month = (new \DateTime($entry['year']."-".$entry['month'].'-'.'01'))->format('Y-m-d');
        $total->year = $entry['year'];

        $total->accountId = $this->account->id;
        $total->name = $this->account->name;
        return $total;
    }

    /**
     * @param Interval $interval
     * @param $sql
     * @return mixed
     */
    protected function buildSelect(Interval $interval)
    {
        $select = $this->getSql()->select('transaction');

        $select->columns(
            [
                'amount' => new Expression('sum(amount)'),
                'month'  => new Expression('month(transaction_date)'),
                'year'   => new Expression('year(transaction_date)'),
            ]
        );

        $select->group('month');
        $select->group('year');

        $select->where->greaterThanOrEqualTo('transaction_date', $interval->getStart()->format('Y-m-d'));
        $select->where->lessThanOrEqualTo('transaction_date', $interval->getEnd()->format('Y-m-d'));
        $select->where->equalTo('to_account', $this->account->id);
        $select->order('month ASC');
        $select->order('year ASC');
        return $select;
    }

}