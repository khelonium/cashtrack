<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logo
 * Date: 11/10/13
 * Time: 8:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\API;


use Refactoring\Interval\SpecificMonth;
use Refactoring\Interval\ThisMonth;
use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class CashFlow extends AbstractRestfulController
{
    public function get($id)
    {
        return new JsonModel(array("type" => 'expense', 'amount' => '30', 'name'=>'ceapa'));
    }

    public function getList()
    {
        $month =  $this->params()->fromQuery('month' , null );
        $list = $this->generateCashflow($month);

        return new JsonModel($list);
    }


    private function generateCashflow($month)
    {


        if ($month == null) {
            $interval = new ThisMonth();
        } else {
            $interval = new SpecificMonth(new \DateTime($month));
        }

        /** @var \Reporter\CashFlow $cashflow */
        $cashflow = $this->getServiceLocator()->get('Reporter\CashFlow');

        return $cashflow->forInterval($interval);


    }



}