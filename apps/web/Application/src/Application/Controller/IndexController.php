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


    public function merchantAction()
    {

    }

    public function predictionAction()
    {

        $sm = $this->getServiceLocator();

        /** @var Database\Account\AccountRepository $accounts */
        $accounts = $sm->get('\Database\Account\Repository');

        /** @var Adapter $adapter */
        $adapter = $sm->get('\Zend\Db\Adapter\Adapter');

        $out = [];

        foreach ($accounts->getByType('expense') as $account) {

            $accountBalance = new \Database\Account\Balance($account);
            $accountBalance->setDbAdapter($adapter);
            $prediction = new \Prediction\PredictAccount($accountBalance);
            $amount = $prediction->thisMonth();

            if ($amount) {
                $out[$account->name] = $amount;
            }

        }

        return new ViewModel(['accounts' => $out]);

    }
}
