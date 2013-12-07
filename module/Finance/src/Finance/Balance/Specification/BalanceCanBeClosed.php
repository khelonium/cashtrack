<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Balance\Specification;

use Finance\Balance\AbstractBalance;
use Finance\Balance\OpenBalance;
use Refactoring\Specification\AbstractSpecification;

class BalanceCanBeClosed extends AbstractSpecification
{
    /**
     *
     * @param AbstractBalance $object
     * @return bool
     */
    public function isSatisfiedBy($object)
    {
        if (!$object instanceof OpenBalance) {
            return false;
        }

        foreach ($object->accounts() as $account) {
            if ($account->getAccount()->type == 'buffer') {
                //if your bank account is in minus you can not close month
                if ($account->getBalance() < 0 ) {
                    return false;
                }
            }
        }
        return true;
    }
}
