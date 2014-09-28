<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logo
 * Date: 11/10/13
 * Time: 8:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\API;



use Finance\Merchant\Repository;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class Merchant extends AbstractRestfulController
{

    /**
     * @var Repository
     */
    private $repository = null;

    public function get($id)
    {
        return new JsonModel([]);
    }

    public function getList()
    {
        return new JsonModel($this->repository->all());
    }


    public function setRepository(Repository $merchants)
    {
        $this->repository = $merchants;
    }
}