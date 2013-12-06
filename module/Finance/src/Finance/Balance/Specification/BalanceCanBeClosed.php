<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Balance\Specification;

use Finance\AccountValue\Specification\CanEndMonth;
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

        $canEndMonth = new CanEndMonth();

        foreach ($object->accounts() as $account) {
            if (!$canEndMonth->isSatisfiedBy($account) ) {
                return false;
            }
        }

        return true;
    }
}
