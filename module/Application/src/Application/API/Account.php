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

    protected $repositoryServiceKey = 'Finance\Account\Repository';

    public function get($id)
    {

        if (! $response = $this->getRepository()->get($id)) {
            $this->response->setStatusCode(404);
            return;
        }

        return new JsonModel($response);
    }

    public function getList()
    {

        $list = $this->getRepository()->all();

        $out = array();
        foreach ($list as $account) {
            $out[] = $account->getArrayCopy();
        }
        return new JsonModel($list);
    }




}