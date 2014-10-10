<?php
/**
 *
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Transaction\Specification;

use Finance\Traits\TransactionRepositoryAwareInterface;
use Finance\Traits\TransactionRepositoryAwareTrait;
use Refactoring\Interval\SpecificMonth;
use Refactoring\Specification\AbstractSpecification;

/**
 * Class MonthWithTransactionSpecification
 * Used to check if a month has transactions or not
 * @package Finance\Month
 */
class MonthWithTransactionSpecification extends AbstractSpecification implements TransactionRepositoryAwareInterface
{

    use \Finance\Traits\TransactionRepositoryAwareTrait;

    /**
     *
     * @param mixed $object
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function isSatisfiedBy($object)
    {
        if (!$object instanceof SpecificMonth) {
            throw new \InvalidArgumentException("I can work only with a SpecificMonth");
        }

        $transactions = $this->getTransactionRepository()->forInterval($object);

        return count($transactions) > 0;
    }
}
