<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 12/24/14
 * Time: 7:54 PM
 */
namespace Application\API\BreakDown;

use Application\View\Error;
use Finance\Reporter\BreakdownInterface;
use Refactoring\Time\Interval\SpecificMonth;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class Month extends AbstractBreakdown
{

    protected function getBreakdown($id)
    {
        $year = $this->getYear();
        return $this->cashflow->expensesFor(new SpecificMonth(new \DateTime("$year-$id-01")));

    }

}


