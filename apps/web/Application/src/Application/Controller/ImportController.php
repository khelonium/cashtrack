<?php
namespace Application\Controller;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;

class ImportController extends IndexController
{


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


        $this->redirect()->toRoute('importDone');

        $container = $this->getSessionContainer();

        $container->transactions = [
            ['description' => 'Abonament BT 24', 'amount' => 1.24, 'date', 'transactionDate' => '2015-01-31'],
            ['description' => 'ELECTRICA FURNIZARE', 'amount' => 162.43, 'transactionDate' => '2015-01-29']
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

    /**
     * @return Container
     */
    protected function getSessionContainer()
    {
        $container = new Container('cashtrack');
        return $container;
    }
}