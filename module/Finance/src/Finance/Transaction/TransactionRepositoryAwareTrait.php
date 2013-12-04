<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Transaction;


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
     * @throws \RuntimeException
     * @return \Finance\Account\Repository
     */
    final protected function getTransactionRepository()
    {
        if (null === $this->transactionRepository) {
            throw new \RuntimeException("Transaction Repository not set");
        }
        return $this->transactionRepository;
    }

}