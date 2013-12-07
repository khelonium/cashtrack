<?php
/**
 * @author Cosmin Dordea <cosmin.dordea@yahoo.com>
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */




namespace Finance\Transaction;



use Refactoring\Interval\IntervalInterface;
use Refactoring\Repository\AbstractRepository;
use Zend\Db\ResultSet\ResultSet;

class Repository extends AbstractRepository
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


    /**
     * @param IntervalInterface $interval
     * @param null $account
     * @return array
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
}
