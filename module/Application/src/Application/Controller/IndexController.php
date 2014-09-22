<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        /** @var \Finance\Account\Repository $repo */
        $repo    = $this->getServiceLocator()->get('Finance\Account\Repository');
        return new ViewModel(array('buffers' => $repo->getByType('buffer')));
    }

    public function accountAction()
    {
        /** @var Adapter $dbAdapter */
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        $statement = $dbAdapter->query(
            "select id_category,category, name, round(sum(amount)) as total ".
            "from expense_overview group by category,name"
        );

        $statement = $dbAdapter->query(
            "select id_category,category as name ,round(sum(amount)) as total ".
            "from expense_overview  group by category"
        );



        $results = $statement->execute();

        $out = [];
        $max = 0;

        foreach($results as $result) {
            $out[]= $result;
            if ($result['total'] > $max) {
                $max = $result['total'];
            }
        }

        $format = function ($el) use ($max) {
            $el['radius']  = $el['total'] / $max * 100;
            $el['cluster'] = (int)$el['id_category'];
            $el['name'] = $el['name'].':'.$el['total'];
            return $el;
        };

        $out = array_map($format,$out);
        return new ViewModel(['nodes' => Json::encode($out)]);

    }
}
