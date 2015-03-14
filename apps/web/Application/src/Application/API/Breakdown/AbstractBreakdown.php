<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/7/15
 * Time: 2:02 PM
 */

namespace Application\API\Breakdown;

use Application\View\Error;
use Finance\Reporter\BreakdownInterface;
use Finance\Reporter\CashFlowInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

abstract class AbstractBreakdown extends AbstractRestfulController
{


    /**
     * @var CashFlowInterface
     */
    protected $cashflow;

    public function __construct(CashFlowInterface $service)
    {
        $this->cashflow = $service;
    }

    /**
     * @param mixed $id
     * @return Error|JsonModel
     */
    public function get($id)
    {

        if ($this->getYear() == null) {
            $this->setBadRequest();
            return new Error("Year is not present");
        }


        return new JsonModel($this->getBreakdown($id));

    }

    /**
     * @return mixed|\Zend\Mvc\Controller\Plugin\Params
     */
    protected function getYear()
    {
        return $this->params('year', null);
    }

    abstract protected function getBreakdown($id);

    /**
     * @return mixed
     */
    protected function setBadRequest()
    {
        return $this->getResponse()->setStatusCode(400);
    }
}
