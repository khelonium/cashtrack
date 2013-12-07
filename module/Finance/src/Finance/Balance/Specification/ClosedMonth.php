<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Balance\Specification;

use Refactoring\Interval\SpecificMonth;
use Refactoring\Repository\GenericRepository;
use Refactoring\Specification\AbstractSpecification;

class ClosedMonth extends AbstractSpecification
{

    /**
     * @var null|\Refactoring\Repository\GenericRepository
     */
    private $balanceRepo = null;

    public function __construct(GenericRepository $repository)
    {

        $this->balanceRepo = $repository;
    }

    /**
     * @param SpecificMonth $object
     * @return bool
     * @throws \BadMethodCallException
     */
    public function isSatisfiedBy($object)
    {
        if (!$object instanceof SpecificMonth) {
            throw new \BadMethodCallException("You are supposed to call this specification only with a specific month");
        }

        $balance = $this->balanceRepo->forKey('month', $object->getStart()->format('Y-m-d'));

        return count($balance) != 0;

    }

}
