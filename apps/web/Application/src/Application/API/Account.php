<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logo
 * Date: 11/10/13
 * Time: 8:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\API;



use Finance\Account\Repository;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class Account extends AbstractController
{

    protected $repositoryServiceKey = 'Database\Account\Repository';


    public function getList()
    {

        $list = $this->getRepository()->all();


        return new JsonModel($list);
    }




}