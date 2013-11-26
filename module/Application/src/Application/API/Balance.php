<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logo
 * Date: 11/10/13
 * Time: 8:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\API;



use Refactoring\Interval\ThisMonth;
use Refactoring\Interval\SpecificMonth;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class Balance extends AbstractRestfulController
{


    public function get($id)
    {
        return new JsonModel();
    }

    public function getList()
    {
        $month    =  $this->params()->fromQuery('month' , null );
        $interval = ($month == null) ?  new ThisMonth(): new SpecificMonth(new \DateTime($month));
        $data     = [];

        if (1 || $interval instanceof ThisMonth) {
            $balance_service = $this->getServiceLocator()->get('\Finance\Balance\BalanceService');
            $data =  $balance_service->getList($this->getBufferIds(), $interval);
        }

        return new JsonModel($data);
    }

    private function getBufferIds()
    {
        $repo     = $this->getServiceLocator()->get('Finance\Account\Repository');
        $accounts = $repo->getByType('buffer');
        $out      = array();

        foreach ($accounts as $acc) {
            $out[]= $acc->id;
        }

        return $out;

    }




}