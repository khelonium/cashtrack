<?php namespace Database\Reporter;

use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;


class YearTotals extends AbstractPeriod
{
    public function totals()
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
}