<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Transaction;

use Database\Transaction\Repository;


/**
 * Class TransactionRepositoryAwareTrait
 * @package Finance\Transaction
 */
trait TransactionRepositoryAwareTrait
{


    /**
     * @var Repository
     */
    private $transactionRepository = null;

    public function setTransactionRepository(Repository $repository)
    {
        $this->transactionRepository = $repository;
    }

    /**
     * @return Repository
     * @throws \RuntimeException
     */
    final protected function getTransactionRepository()
    {
        if (null === $this->transactionRepository) {
            throw new \RuntimeException("Transaction Repository not set");
        }
        return $this->transactionRepository;
    }

}