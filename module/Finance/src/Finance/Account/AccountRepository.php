<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 * Date: 12/1/13
 */

namespace Finance\Account;


use Refactoring\Repository\AbstractRepository;
use Zend\Db\TableGateway\TableGateway;

class AccountRepository extends  AbstractRepository
{
    /**
     * Returns the table gateway used by the main entity
     * @return TableGateway
     */
    protected function gateway()
    {
        return $this->getServiceManager()->get('\Finance\Dao\AccountGateway');
    }

    /**
     *
     */
    public function getList($idList)
    {
        $select = $this->gateway()->getSql()->select();
        $select->where->in('id_account',$idList);

        $out = [];

        foreach ($this->gateway()->selectWith($select) as $result) {
            $out[] = $result;
        }

        return;
    }

}