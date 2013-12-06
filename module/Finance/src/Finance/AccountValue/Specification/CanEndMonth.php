<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\AccountValue\Specification;

use Finance\AccountValue\AccountValue;
use Refactoring\Specification\AbstractSpecification;

class CanEndMonth extends AbstractSpecification
{
    /**
     *
     * @param AccountValue $object
     * @return bool
     */
    public function isSatisfiedBy($object)
    {
        if ($object->getAccount()->type != 'buffer') {
            return true;
        }

        return ($object->getBalance() >=0);
    }
}
