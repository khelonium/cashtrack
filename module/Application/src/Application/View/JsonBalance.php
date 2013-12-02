<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Application\View;

use Finance\Balance\AbstractBalance;
use Zend\View\Model\JsonModel;

class JsonBalance extends JsonModel
{
    public function __construct($balances = null, $options = null)
    {
        $out = array();

        if ($balances instanceof AbstractBalance) {
            $out = $this->getBalanceData($balances);
        } elseif (is_array($balances)) {
            foreach ($balances as $balance) {
                $data[] = $this->getBalanceData($balance);
            }
        }

        parent::__construct($out, $options);
    }

    private function getBalanceData(AbstractBalance $balance)
    {
        $data = [];
        //fixme add month
        $data['credit']   = $balance->getCredit();
        $data['debit']    = $balance->getDebit();
        $data['accounts'] = [];

        foreach ($balance->accounts() as $account) {
            $data['accounts'][] = $account->toArray();
        }

        return $data;
    }
}