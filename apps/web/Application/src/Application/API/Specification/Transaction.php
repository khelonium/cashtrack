<?php

namespace Application\API\Specification;

use Database\Transaction\Repository\Specification;
use Database\Repository\SqlSpecification;
use Refactoring\Time\Interval\SpecificMonth;
use Refactoring\Time\Interval\ThisMonth;

class Transaction extends Specification implements SqlSpecification
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


}

