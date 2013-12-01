<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Balance\Specification;

use Refactoring\Interval\SpecificMonth;
use Refactoring\Specification\AbstractSpecification;
use Refactoring\Specification\unknown_type;

class ClosedMonth extends AbstractSpecification
{


    /**
     * @param unknown_type $object
     * @return bool
     * @throws \BadMethodCallException
     */
    public function isSatisfiedBy($object)
    {
        if (!$object instanceof SpecificMonth) {
            throw new \BadMethodCallException("You are supposed to call this specification only with a specific month");
        }

        return false;
    }

}