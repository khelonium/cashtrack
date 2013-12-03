<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logo
 * Date: 11/10/13
 * Time: 8:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\API;



use Application\Library\Interval\ThisOrSpecificMonth;
use Application\View\JsonBalance;
use Finance\Balance\BalanceService;
use Finance\Balance\SubsetBalance;
use Finance\Transaction\Transaction;
use Refactoring\Interval\ThisMonth;
use Refactoring\Interval\SpecificMonth;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class Balance extends AbstractRestfulController
{

    /**
     * Should return balance only for one model
     * @param mixed $id
     * @return mixed|JsonModel
     */
    public function get($id)
    {
        return new JsonModel();
    }

    public function create($data)
    {

        //fixme this is to be moved in close month service
        //a specification will check if the month can be closed
        // no negative amount allowed at end of month
        $working_month = new SpecificMonth(new \DateTime($data['month']));
        $balance_service = $this->getServiceLocator()->get('\Finance\Balance\BalanceService');
        $balance = $balance_service->getBalance($working_month);
        $buffers = new SubsetBalance($balance, 'buffer');


        $next = $working_month->getEnd()->add(new \DateInterval('P1D'));
        $date = $next->format('Y-m-d');

        $repo = $this->getServiceLocator()->get('Finance\Transaction\Repository');


        foreach ($buffers->accounts() as $buffer) {
            $transaction                 = new Transaction();
            $transaction['amount']       = $buffer->getBalance();
            $transaction['date']         = $date;
            $transaction['reference']    = 'Previous month balance';
            $transaction['from_account'] =  55;
            $transaction['to_account']   =  $buffer->getAccount()['id'];
            $repo->add($transaction);
        }

        return new JsonModel();

    }


    /**
     * @fixme balance needs to be generic
     * @fixme it will be required to select any type of buffer
     * @return mixed|JsonModel
     */
    public function getList()
    {
        $month    =  $this->params()->fromQuery('month' , null );

        $day = ($month === null)? new \DateTime() : new \DateTime($month);

        $interval = new SpecificMonth($day);

        /** @var BalanceService $balance_service */
        $balance_service = $this->getServiceLocator()->get('\Finance\Balance\BalanceService');
        $balance  = $balance_service->getBalance($interval);

        return new JsonBalance(new SubsetBalance($balance, 'buffer'));

    }


}