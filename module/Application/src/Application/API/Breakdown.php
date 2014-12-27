<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 12/24/14
 * Time: 7:54 PM
 */

namespace Application\API;


use Application\View\Error;
use Finance\Reporter\BreakdownInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class Breakdown extends AbstractRestfulController
{

    /**
     * @var \Finance\Reporter\BreakdownInterface
     */
    private $service;

    public function __construct(BreakdownInterface $service)
    {
        $this->service = $service;
    }


    public function get($id)
    {

        if ($this->getYear() == null) {
            $this->setBadRequest();
            return new Error("Year is not present");
        }


        $type = $this->getType();


        switch($type) {
            case 'week':
                $response = new JsonModel($this->service->week($this->getYear(), $id));
                break;
            case 'month':
                $response = new JsonModel($this->service->month($this->getYear(), $id));
                break;
            default:
                $this->setBadRequest();
                $response =  new Error('Invalid breakdown type '.$this->getType());
            break;
        }

        return $response;

    }

    protected function getType()
    {
        return $this->params('type',null);
    }

    protected function getYear()
    {
        return $this->params('year',null);
    }

    protected function setBadRequest()
    {
        return $this->getResponse()->setStatusCode(400);
    }

}