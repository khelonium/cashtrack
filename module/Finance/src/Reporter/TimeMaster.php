<?php

namespace Reporter;


use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class TimeMaster implements TimeViewInterface, AdapterAwareInterface
{
    use AdapterAwareTrait;

    public function yearTotals()
    {
        $select = $this->getBaseSelect();


        $select->columns(
            [
                'unit_nr' => new Expression('year(transaction_date)'),
                'amount'  => new Expression('sum(amount)')
            ]
        );
        return $this->getSummary($select);

    }


    /**
     * @param $year
     * @return array
     */
    public function weekTotals($year)
    {

        $select = $this->getBaseSelect();

        $select->columns(
            [
                'unit_nr' => new Expression('week(transaction_date,1)'),
                'amount' => new Expression('sum(amount)')
            ]
        );
        $select->where->like('transaction_date', "%$year%");
        return $this->getSummary($select);

    }


    public function monthTotals($year)
    {

        $select = $this->getBaseSelect();

        $select->columns(
            [
                'unit_nr' => new Expression('month(transaction_date)'),
                'amount' => new Expression('sum(amount)')
            ]
        );
        $select->where->like('transaction_date', "%$year%");

        return $this->getSummary($select);
    }


    protected function getSummary(Select $select)
    {

        $out = [];

        foreach ($this->getSql()->prepareStatementForSqlObject($select)->execute() as $result) {
            $out [] = $result;
        }

        return $out;

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
     * @return \Zend\Db\Sql\Select
     */
    protected function getBaseSelect()
    {
        $sql = $this->getSql();

        $select = $sql->select('transaction');

        $select->join('account', new Expression("account.id = transaction.to_account"), []);

        $select->where->equalTo('account.type', 'expense');
        $select->group('unit_nr');
        return $select;
    }
}
