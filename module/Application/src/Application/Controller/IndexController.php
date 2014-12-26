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
        $repo    = $this->getServiceLocator()->get('Database\Account\Repository');
        return new ViewModel(array('buffers' => $repo->getByType('buffer')));
    }


    public function visualAction()
    {

    }


    public function merchantAction()
    {

    }

    public function weekAction()
    {

        return new ViewModel(['week' => $this->params('week')]);
    }

    public function monthAction()
    {
        return new ViewModel(['month' => $this->params('month')]);
    }

    public function weeklyAction()
    {

    }

    public function monthlyAction()
    {

    }
}
