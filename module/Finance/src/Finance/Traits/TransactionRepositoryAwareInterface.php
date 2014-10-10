<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Traits;

use Finance\Transaction\TransactionRepositoryInterface;

interface TransactionRepositoryAwareInterface
{
    /**
     * @param TransactionRepositoryInterface $repository
     * @return null
     */
    public function setTransactionRepository(TransactionRepositoryInterface $repository);
} 