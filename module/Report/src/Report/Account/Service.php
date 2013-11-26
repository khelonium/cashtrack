<?php
/**
 * @author Cosmin Dordea <cosmin.dordea@yahoo.com>
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */

namespace Report\Account;

use Refactoring\Interval\IntervalInterface;
use Report\Transaction\Entity;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;


/**
 * Necessary db interface for Account class
 * Class Repository
 * @package Report\Expense
 */
class Service
{

    /**
     * @var null|\Zend\Db\Adapter\Adapter
     */
    private $adapter = null;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }


    public function get(\DateTime $start, \DateTime $end)
    {

        $start_date = $start->format('Y-m-d');
        $end_date   = $end->format('Y-m-d');
        $sql = "SELECT e.name, round(sum(t.amount),2) as total FROM `transaction`
            t left join account e  on e.id = t.to_account where t.to_account is not null and
            transaction_date between '$start_date' and '$end_date'
            group by e.name";

        $statement = $this->adapter->query($sql);


        $resultSet = new ResultSet(); // Zend\Db\ResultSet\ResultSet
        $resultSet->setArrayObjectPrototype(new  Entity());
        $resultSet->initialize($statement->execute());

        return $resultSet;
    }
}

