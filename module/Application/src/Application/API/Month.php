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

class Month extends AbstractController
{
    protected $repositoryServiceKey = 'Finance\Account\Repository';

    /**
     * @return mixed|void
     */
    public function getList()
    {
        return new JsonModel($this->getMonths());
    }

    public function get($id)
    {
        return new JsonModel($this->getMonths($id));
    }



    /**
     * @return array|object
     */
    private function getAdapter()
    {
        /** @var  $dbAdapter */
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        return $dbAdapter;
    }

    /**
     * @param $dbAdapter
     * @return mixed
     */
    private function getMonths($year = null)
    {

        $where = '';

        if ($year) {
            $where = "WHERE month like '%$year%'";
        }

        $sql = "select distinct month from expense_overview  $where order by month asc ";

        $statement = $this->getAdapter()->query($sql);
        return $statement->execute();
    }


}