<?php namespace Database\Reporter;

use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;


class MonthTotals extends AbstractPeriod
{
    public function totals($year)
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
}