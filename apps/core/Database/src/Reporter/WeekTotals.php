<?php namespace Database\Reporter;

use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;


class WeekTotals extends AbstractPeriod
{
    public function totals($year)
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
}