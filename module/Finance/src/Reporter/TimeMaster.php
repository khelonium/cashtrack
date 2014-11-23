<?php

namespace Reporter;


use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterAwareTrait;

class TimeMaster implements TimeReporterInterface, AdapterAwareInterface
{
    use AdapterAwareTrait;

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
            where transaction_date like '%$year%'
            group by unit_nr
            ;
        ",
            $adapter::QUERY_MODE_EXECUTE
        );


        return $this->putInArray($results);

    }
    public function monthTotals($year)
    {
        /** @var Adapter $adapter */
        $adapter = $this->adapter;

        $results = $adapter->query(
            "
            SELECT month(transaction_date) as unit_nr,sum(amount) as amount from transaction
            where transaction_date like '%$year%'
            group by unit_nr
            ;
        ",
            $adapter::QUERY_MODE_EXECUTE
        );

        return $this->putInArray($results);

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

} 