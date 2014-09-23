<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logo
 * Date: 11/10/13
 * Time: 8:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\API;



use Application\View\JsonOverview;
use Finance\Account\Repository;
use Zend\Db\Adapter\Adapter;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class Overview extends AbstractController
{

    protected $repositoryServiceKey = 'Finance\Account\Repository';


    public function get($id)
    {



        /** @var Adapter $dbAdapter */
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

//        $statement = $dbAdapter->query(
//            "select id_category,category, name, round(sum(amount)) as total ".
//            "from expense_overview where month like '%$id%' group by category,name"
//        );


        $statement = $dbAdapter->query(
            "select id_category,category as name ,round(sum(amount)) as total ".
            "from expense_overview   where month like '%$id%'  group by category"
        );


       return new JsonOverview($statement->execute());
    }



}