<?php
/**
 * @author Cosmin Dordea <cosmin.dordea@yahoo.com>
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */
namespace Database\Transaction;

use Finance\Transaction\Transaction;
use Finance\Transaction\TransactionRepositoryInterface;
use Library\AbstractRepository;
use Refactoring\Time\Interval\IntervalInterface;

class Repository extends AbstractRepository implements TransactionRepositoryInterface
{


    /**
     * @var \Zend\Db\TableGateway\TableGateway
     */
    private $gateway = null;

    /**
     * @return \Zend\Db\TableGateway\TableGateway
     */
    protected function gateway()
    {
        if (null == $this->gateway) {
            $this->gateway = $this->getServiceManager()->get('\Finance\Dao\TransactionGateway');
        }

        return $this->gateway;
    }


    public function delete(Transaction $transaction)
    {
        $this->gateway()->delete(['id' => $transaction->id]);
    }

    /**
     * @param IntervalInterface $interval
     * @return array
     * @internal param null $account
     * @todo REFACTOR WITH specification
     */
    public function forInterval(IntervalInterface $interval)
    {

        $select = $this->getSelect();
        $select->where->greaterThanOrEqualTo('transaction_date', $interval->getStart()->format('Y-m-d'));
        $select->where->lessThanOrEqualTo('transaction_date', $interval->getEnd()->format('Y-m-d'));


        $result = $this->gateway()->selectWith($select);
        $out    = array();

        foreach ($result as $entry) {
            $out[] = $entry;
        }
        return $out;
    }


    /**
     * @return \Zend\Db\Sql\Select
     */
    private function getSelect()
    {
        return $this->gateway()->getSql()->select();
    }

    /**
     * Convenience for tests, alias for add, but from array
     * @param $data
     * @return Transaction
     */
    public function create($data)
    {
        $transaction = new Transaction();
        $transaction->exchangeArray($data);
        $this->add($transaction);
        return $transaction;
    }
}
