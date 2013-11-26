<?php

namespace Application\API\Specification;

use Finance\Transaction\Repository\Specification;
use Refactoring\Db\SqlSpecification;
use Refactoring\Interval\SpecificMonth;
use Refactoring\Interval\ThisMonth;
use Refactoring\Specification\SpecificationInterface;

class Transaction extends Specification implements SpecificationInterface, SqlSpecification
{

    /**
     * @param \Zend\Mvc\Controller\Plugin\Params $params
     */
    public function __construct($params)
    {
        $month   =  $params()->fromQuery('month' , null );
        $account =  $params()->fromQuery('accountId' , null );
        $interval = ($month == null) ?  new ThisMonth(): new SpecificMonth(new \DateTime($month));

        if ($account) {
            $this->equalTo('toAccount', $account);
        }

        $this->greaterOrEqual('date', $interval->getStart()->format('Y-m-d'));
        $this->lessOrEqual('date',    $interval->getEnd()->format('Y-m-d'));

    }

    /**
     * Would have returned false if a top level param would been mandatory
     * @param \Zend\Mvc\Controller\Plugin\Params
     * $object
     * @return bool
     */
    public function isSatisfiedBy($query)
    {
        return true;
    }


}

