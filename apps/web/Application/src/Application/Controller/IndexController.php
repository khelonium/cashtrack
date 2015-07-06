<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Database\Account\AccountSum;
use Library\Collection;
use Prediction\Cadence;
use Refactoring\Time\Interval;
use Refactoring\Time\Interval\LastMonth;
use Zend\Db\Adapter\Adapter;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    private $transactions;

    public function indexAction()
    {

    }

    public function merchantAction()
    {

    }

    public function importAction()
    {

    }

    public function processAction()
    {

        $request = $this->getRequest();
        if (false == $request->isPost()) {
            $this->redirect()->toRoute('import');
            return;
        }

        $this->moveUploadedFile();

        $this->redirect()->toRoute('importDone');


        $container = $this->getSessionContainer();
        $container->transactions = [
            ['description' => 'Abonament BT 24', 'amount' =>1.24 , 'date', 'transactionDate' => '2015-01-31'],
            ['description' => 'ELECTRICA FURNIZARE', 'amount' =>162.43, 'transactionDate' => '2015-01-29']
        ];
    }

    public function doneAction()
    {

        if (!$this->getSessionContainer()->offsetExists('transactions')) {
            $this->redirect()->toRoute('import');
            return;
        }

        $transactions = $this->getSessionContainer()->transactions;
        $this->getSessionContainer()->offsetUnset('transactions');
        return new ViewModel(['transactions' => $transactions]);

    }

    private function moveUploadedFile()
    {

    }

    /**
     * @return Container
     */
    protected function getSessionContainer()
    {
        $container = new Container('cashtrack');
        return $container;
    }

}
