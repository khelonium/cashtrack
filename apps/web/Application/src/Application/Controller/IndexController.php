<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Prediction\Cadence;
use Refactoring\Time\Interval;
use Refactoring\Time\Interval\LastMonth;
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

        $cadences = [];
        $ignore = [77, 1, 10, 6 , 8 ,42, 64];

        foreach ($accounts->getByType('expense') as $account) {
            if (false !== array_search($account->id, $ignore)) {
                continue;
            }
            $accountBalance = new \Database\Account\AccountSum($account);
            $accountBalance->setDbAdapter($adapter);
            $prediction = new \Prediction\PredictAccount($accountBalance);
            $cadence = new Cadence($accountBalance->totalFor($this->getInterval()));
            $cadences[$account->name] =  $cadence->getCadence();
            $amount = $prediction->thisMonth();

            if ($amount) {
                $out[$account->name] = $amount;
            }

        }

        return new ViewModel(['accounts' => $out, 'cadences' => $cadences]);

    }

    /**
     * @return LastMonth
     */
    protected function getInterval()
    {
        $end = (new LastMonth())->getStart();
        $start = clone $end;
        $start->sub(new \DateInterval('P1Y'));
        return new Interval($start, $end);
    }
}
