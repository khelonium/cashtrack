<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 * Date: 12/1/13
 */

namespace Database\Account;


use Finance\Account\Account;
use Refactoring\Repository\AbstractRepository;


class AccountRepository extends AbstractRepository
{

    public function addFromName($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException(" Provided argument is not string");
        }

        return $this->add(new Account(array('name' => $name)));
    }


    /**
     * @param $idList
     */
    public function getList(array $idList)
    {
        $select = $this->gateway()->getSql()->select();
        $select->where->in('id_account', $idList);

        $out = [];

        foreach ($this->gateway()->selectWith($select) as $result) {
            $out[] = $result;
        }

        return;
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
