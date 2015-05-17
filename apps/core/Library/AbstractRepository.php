<?php
namespace Library;

use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

abstract class AbstractRepository implements ServiceManagerAwareInterface
{
    /**
     *
     * @var ServiceManager
     */
    private $serviceManager = null;


    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     *
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Returns all entities
     * @return array
     */
    public function all()
    {
        $out = array();
        $result = $this->gateway()->select();
        foreach ($result as $entry) {
            $out[] = $entry;
        }
        return $out;
    }


    /**
     * @param $id
     * @return bool |Entity
     */
    public function get($id)
    {
        return  $this->gateway()->select(array('id' => $id))->current();
    }

    /**
     * You are allowed to guess three times what this does
     * @param Entity $entity
     */
    public function add(Entity $entity)
    {

        $this->gateway()->insert(
            $entity->getDatabaseCopy()
        );

        $entity->id = $this->gateway()->getLastInsertValue();
    }


    public function update(Entity $entity)
    {
        $copy = $entity->getDatabaseCopy();
        $id = $copy['id'];

        unset($copy['id']);

        $this->gateway()->update($copy, array('id' => $id));
    }

    /**
     * Returns the table gateway used by the main entity
     * @return TableGateway
     */
    abstract protected function gateway();

    /**
     * @param SqlSpecification $spec
     * @return array
     */
    public function fromSpecification(SqlSpecification $spec)
    {
        $mapper = $this->gateway()->getResultSetPrototype()->getArrayObjectPrototype()->getDatabaseMapper();
        $select = $spec->specify($this->gateway()->getSql()->select(), $mapper);

        $result = $this->gateway()->selectWith($select);
        $out = array();

        foreach ($result as $entry) {
            $out[] = $entry;
        }

        return $out;
    }

}