<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 07/10/14
 * Time: 02:09
 */

namespace Database\Balance;


use Finance\Balance\BalancePersistenceInterface;
use Finance\Balance\OpenBalance;
use Refactoring\Repository\GenericRepository;

class Repository extends GenericRepository implements BalancePersistenceInterface
{
    /**
     * @param $balance
     */
    public function recordBalanceResult(OpenBalance $balance)
    {

        $record = new Balance();
        $record['month'] = $balance->getMonth()->getStart()->format('Y-m-d');
        $record['balance'] = $balance->getBalance();
        $record['debit'] = $balance->getDebit();
        $record['credit'] = $balance->getCredit();

        $this->add($record);
    }

    /**
     * check if a month exists
     * @param \DateTime $day
     * @return bool
     */
    public function monthIsRecorded(\DateTime $day)
    {
        $result = $this->forKey('month', $day->format('Y-m-d'));
        return count($result) != 0;
    }
}