<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Application\View;

use Finance\Balance\AbstractBalance;
use Finance\Balance\ClosedBalance;
use Finance\Balance\OpenBalance;
use Finance\Balance\SubsetBalance;
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


        if ($balance instanceof OpenBalance) {
            $data['start']  = $balance->getMonth()->getStart()->format('Y-m-d');
            $data['end']    = $balance->getMonth()->getEnd()->format('Y-m-d');
            $data['status'] = 'open';
        }

        if ($balance instanceof ClosedBalance) {
            $data['status'] = 'closed';
        }


        if ($balance instanceof SubsetBalance) {
            $data['status'] = 'subset';

        }

        $data['accounts'] = [];
        foreach ($balance->accounts() as $account) {
            $data['accounts'][] = $account->toArray();
        }

        return $data;
    }
}