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

        $files = $request->getFiles();
        $filter = new \Zend\Filter\File\RenameUpload("./data/uploads/");
        $filter->setOverwrite(true);
        $filter->setUseUploadName(true);


        $file_data = $filter->filter($files['transaction_file']);

        $sm = $this->getServiceLocator();
        $merchant_service = $sm->get('\Database\Merchant\Repository');

        $out = [];
        foreach ($merchant_service->all() as $merchant) {
            $out[] = $merchant;
        }
        $parser =  new \Import\BT\Parser(new \Import\BT\Matcher($out));

        $import = new \Import\BT\Importer($sm->get('\Database\Transaction\Repository'), $parser);

        $container = $this->getSessionContainer();
        $container->transactions = $import->import($file_data['tmp_name']);

        $this->redirect()->toRoute('importDone');



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