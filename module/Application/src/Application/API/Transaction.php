<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logo
 * Date: 11/10/13
 * Time: 8:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\API;



use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class Transaction extends AbstractController
{

    protected $repositoryServiceKey = 'Finance\Transaction\Repository';

    /**
     * @param mixed $id
     * @return array|mixed|JsonModel
     */
    public function get($id)
    {

        if (! $response = $this->getRepository()->get($id)) {
            $this->response->setStatusCode(404);
            return array(content =>"Transaction not found");
        }

        return new JsonModel($response);
    }

    public function getList()
    {

        $spec = new \Application\API\Specification\Transaction($this->params());
        $list = $this->getRepository()->fromSpecification($spec);


        foreach ($list as $account) {
            $out[] = $account->getArrayCopy();
        }

        return new JsonModel($list);
    }

    /**
     * Update an existing resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return mixed
     */
    public function update($id, $data)
    {
       $entity = new Transaction($data);
       $this->getRepository()->update($entity);

       return new JsonModel($entity);
    }

    /**
     * Create a new resource
     *
     * @param  mixed $data
     * @return mixed
     */
    public function create($data)
    {

        $entity = new Transaction($data);


        try {
            $this->getRepository()->add($entity);
        } catch ( \Exception $e) {
            $this->response->setStatusCode(500);
            return new JsonModel(array(
                'content' => 'Internal server error '. $e->getMessage()
            ));
        }

        return new JsonModel($entity);
    }

}