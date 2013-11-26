<?php


namespace Finance\Balance;

use Refactoring\Interval\IntervalInterface;
use Zend\Db\Adapter\Adapter;

/**
 * Class BalanceService
 * Computes transients balances for any interval
 * @package Finance\Balance
 */
class BalanceService
{

    /**
     * @var null|\Zend\Db\Adapter\Adapter
     */
    private $adapter = null;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getList($accounts, IntervalInterface $interval)
    {

        $out =  new \ArrayObject();
        foreach ($accounts as $account) {
            $out[]= $this->get($account, $interval);
        }

        return $out;
    }

    /**
     * Returns the balance for a certain account
     * @param $account account id
     * @param IntervalInterface $interval
     * @return Balance
     */
    public function get($account, IntervalInterface $interval)
    {
        $sql        = $this->getSqlFor($account, $interval);
        $statement = $this->adapter->query($sql);
        $result = $statement->execute();

        $current = $result->current();

        return new Balance(
            array(
                'idAccount' =>  $account,
                'credit'    =>  $current['credit'],
                'debit'     =>  $current['debit'],
                'name'      => $current['name'],
                'month'     =>  $interval->getStart()->format('Y-m-d'),
            )
        );

    }

    private function getSqlFor($account, IntervalInterface $interval)
    {
       $sql =  "SELECT
            (SELECT round(SUM(amount),2)
                from transaction where to_account=%s
                and  transaction_date >= '%s'
                and transaction_date <= '%s')
            AS credit ,
           (SELECT round(SUM(amount),2) from transaction
                WHERE from_account=%s and transaction_date >= '%s' and transaction_date <= '%s')
            as debit,
            (SELECT name from account where id = %s ) as name
            ";


        return sprintf(
            $sql,
            $account,
            $interval->getStart()->format('Y-m-d'),
            $interval->getEnd()->format('Y-m-d'),
            $account,
            $interval->getStart()->format('Y-m-d'),
            $interval->getEnd()->format('Y-m-d'),
            $account


        );

    }


}