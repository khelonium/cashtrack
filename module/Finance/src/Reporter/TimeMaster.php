<?php

namespace Reporter;


use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterAwareTrait;

class TimeMaster implements TimeReporterInterface, AdapterAwareInterface
{
    use AdapterAwareTrait;

    public function yearTotals()
    {
        return $this->getSummary(
            " SELECT year(transaction_date) as unit_nr,sum(amount) as amount from transaction
            left join account on account.id = transaction.to_account
            and account.type = 'expense'
            group by unit_nr"
        );
    }


    /**
     * @param $year
     * @return array
     */
    public function weekTotals($year)
    {
        $adapter = $this->adapter;

        $results = $adapter->query(
            "
            SELECT week(transaction_date,1) as unit_nr,sum(amount) as amount from transaction
            left join account on account.id = transaction.to_account
            where transaction_date like '%$year%'
            and account.type = 'expense'
            group by unit_nr
            ;
        ",
            $adapter::QUERY_MODE_EXECUTE
        );


        return $this->putInArray($results);

    }
    public function monthTotals($year)
    {


        return $this->getSummary(
            "
            SELECT month(transaction_date) as unit_nr,sum(amount) as amount from transaction
            left join account on account.id = transaction.to_account
            where transaction_date like '%$year%'
            and account.type = 'expense'
            group by unit_nr
            ;
        ");

    }

    /**
     * @param $results
     * @return array
     */
    private function putInArray($results)
    {
        $out = [];
        foreach ($results as $result) {
            $out [] = $result;
        }
        return $out;
    }

    /**
     * @param $sql
     * @return array
     */
    protected function getSummary($sql)
    {
        /** @var Adapter $adapter */
        $adapter = $this->adapter;

        $results = $this->adapter->query(
            $sql,
            $adapter::QUERY_MODE_EXECUTE
        );

        return $this->putInArray($results);
    }

} 