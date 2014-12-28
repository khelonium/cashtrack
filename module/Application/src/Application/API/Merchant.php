<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logo
 * Date: 11/10/13
 * Time: 8:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\API;



use Finance\Merchant\Merchant as MerchantEntity;
use Database\Merchant\Repository;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class Merchant extends AbstractRestfulController
{

    /**
     * @var Repository
     */
    private $repository = null;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function get($id)
    {
        throw new \Exception("1");
        return new JsonModel([]);
    }

    public function getList()
    {
        return new JsonModel($this->repository->all());
    }

    /**
     * @param mixed $id
     * @param mixed $data
     * @return mixed|void
     */
    public function update($id, $data)
    {
        $merchant = new MerchantEntity($data);
        $this->repository->add($merchant);
        return new JsonModel($merchant);
    }

    public function create($data)
    {
        $this->repository->add(new MerchantEntity($data));
        return new JsonModel();
    }
}