<?php
/**
 * @author Cosmin Dordea <cosmin.dordea@yahoo.com>
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */




namespace Finance\Account;

use Refactoring\Db\Entity as RefactoringEntity;

use Refactoring\Repository\AbstractRepository;

class Repository extends AbstractRepository
{

    public function addFromName($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException(" Provided argument is not string");
        }

        return $this->add(new Account(array('name' => $name)));
    }



    /**
     * @param $accountName
     * @return Account
     */
    public function addOrLoad($accountName)
    {

        $result =$this->gateway()->select(array('name' => $accountName));

        if (count($result) == 0) {
            return $this->addFromName($accountName);
        }

        return $result->current();

    }


    /**
     * @param $type possible values income|expense|buffer|saving
     * @return array
     */
    public function getByType($type)
    {

        $out = array();
        $result = $this->gateway()->select(array('type' => $type));
        foreach ($result as $entry) {
            $out[] = $entry;
        }
        return $out;

    }


    /**
     * @var \Zend\Db\TableGateway\TableGateway
     */
    private $accountTable = null;

    /**
     * @return \Zend\Db\TableGateway\TableGateway
     */
    protected function gateway()
    {
        if (null == $this->accountTable) {
            $this->accountTable = $this->getServiceManager()->get('\Finance\Dao\AccountGateway');
        }

        return $this->accountTable;
    }

}