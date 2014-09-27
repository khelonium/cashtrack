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

        $detailed = false;

        return new JsonOverview(($detailed)? $this->getDetailed($id):$this->getRegular($id));
    }

    /**
     * @param $id
     * @param $dbAdapter
     * @return mixed
     */
    private function getDetailed($month, $idCategory = null)
    {

        $adapter = $this->getAdapter();

        $and = '';

        if ($idCategory) {
            $and = " AND id_category = $idCategory ";
        }

        $statement = $adapter->query(
            "select id_category, name ,round(sum(amount)) as total " .
            "from expense_overview   where month like '%$month%' $and group by name"
        );


        return $this->asArray($statement->execute());
    }

    /**
     * @param $id
     * @param $dbAdapter
     * @return mixed
     */
    private function getRegular($month)
    {

        $expand = $this->params()->fromQuery('expand');
        $and    = '';
        $out = [];

        if ($expand) {
            $and = " AND id_category != $expand ";
            $out = $this->getDetailed($month, $expand);
        }

        $statement = $this->getAdapter()->query(
            "select id_category, category  as name,round(sum(amount)) as total " .
            "from expense_overview   where month like '%$month%' $and group by category"
        );

        return array_merge($out, $this->asArray($statement->execute()));

    }


    /**
     * @return Adapter
     */
    private function getAdapter()
    {
        /** @var Adapter $dbAdapter */
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        return $dbAdapter;
    }

    /**
     * @param $result
     * @return array
     */
    private function asArray($result)
    {
        $out = [];

        foreach ($result as $entry) {
            $out[] = $entry;
        }
        return $out;
    }


}