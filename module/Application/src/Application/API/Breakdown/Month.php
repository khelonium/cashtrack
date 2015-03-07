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
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class Month extends AbstractBreakdown
{

    protected function getBreakdown($id)
    {
        return $this->service->month($this->getYear(), $id);
    }

}


