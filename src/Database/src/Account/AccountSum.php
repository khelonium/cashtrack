<?php
namespace Database\Account;

use Database\Account\Exception\NoAccountSet;
use Finance\Account\Account;
use Finance\Account\AccountSum as AccountSumInterface;
use Finance\Cashflow\AccountTotal;
use Library\Collection;
use Refactoring\Time\Interval;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Sql;

class AccountSum implements AccountSumInterface, AdapterAwareInterface
{
    use AdapterAwareTrait;

    /**
     * @var Account
     */
    private $account;

    public function setAccount(Account $account = null)
    {
        $this->account = $account;
    }

    /**
     * Side free effect.
     * @param Account $account
     * @return AccountSum
     */
    public function forAccount(Account $account)
    {
        $sum = clone $this;
        $sum->account = $account;
        return $sum;
    }

    /**
     * @param Interval $interval
     * @return \Library\Collection
     */
    public function totalFor(Interval $interval)
    {

        if (null == $this->account) {
            throw new NoAccountSet();
        }

        $result =  $this->getSql()
            ->prepareStatementForSqlObject($this->buildSelect($interval))
            ->execute();

        $out = [];

        foreach ($result as $entry) {
            $out[] = $this->buildEntry($entry);
        }

        return new Collection($out);
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
     * @return AccountTotal
     */
    protected function buildEntry($entry)
    {
        $total = new AccountTotal();
        $total->amount = $entry['amount'];
        $total->month = (new \DateTime($entry['year']."-".$entry['month'].'-'.'01'))->format('Y-m-d');
        $total->year = $entry['year'];

        $total->accountId = $this->account->id;
        $total->name = $this->account->name;
        return $total;
    }

    /**
     * @param Interval $interval
     * @return \Zend\Db\Sql\Select
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