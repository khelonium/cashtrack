<?php
namespace Database\Account;

use Finance\Account\Account;
use Library\AbstractRepository;

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
        return $this->gateway()->select(array('type' => $type));
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

    /**
     * Can add directly an array
     * @param $input
     * @return Account
     */
    public function quickAdd($input)
    {
        $account = new Account($input);
        $this->add($account);
        return $account;
    }

}
