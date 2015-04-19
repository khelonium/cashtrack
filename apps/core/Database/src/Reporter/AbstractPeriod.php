<?php
namespace Database\Reporter;

use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;

class AbstractPeriod implements AdapterAwareInterface
{

    use AdapterAwareTrait;

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

    /**
     * @return Sql
     */
    protected function getSql()
    {
        $sql = new Sql($this->adapter);
        return $sql;
    }

    protected function getSummary(Select $select)
    {

        $out = [];

        foreach ($this->getSql()->prepareStatementForSqlObject($select)->execute() as $result) {
            $out [] = $result;
        }

        return $out;

    }

}