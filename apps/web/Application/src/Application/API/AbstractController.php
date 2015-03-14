<?php

namespace Application\API;

use Zend\Mvc\Controller\AbstractRestfulController;

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