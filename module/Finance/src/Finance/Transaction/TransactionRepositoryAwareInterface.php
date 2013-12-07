<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Transaction;


interface TransactionRepositoryAwareInterface
{
    /**
     * @param Repository $repository
     * @return null
     */
    public function setTransactionRepository(Repository $repository);
} 