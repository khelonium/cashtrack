<?php

namespace Application\API;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

abstract class AbstractController extends AbstractRestfulController
{

    /**
     * Override this so that the correct repository is created
     * @var string
     */
    protected $repositoryServiceKey = null;

    /**
     * @var Repository
     */
    private $_repo = null;

    /**
     * @param mixed $id
     * @return array|mixed|JsonModel
     */
    public function get($id)
    {

        if (!$response = $this->getRepository()->get($id)) {
            $this->response->setStatusCode(404);
            return array('content' => "Resource not found");
        }

        return new JsonModel($response);
    }

    /**
     * @return repository
     */
    protected function getRepository()
    {
        if (null === $this->_repo) {
            $this->_repo = $this->getServiceLocator()->get($this->repositoryServiceKey);
        }

        return $this->_repo;
    }
}