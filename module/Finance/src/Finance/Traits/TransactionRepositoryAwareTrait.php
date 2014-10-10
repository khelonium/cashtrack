<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Traits;

use Finance\Transaction\Repository;
use Finance\Transaction\TransactionRepositoryInterface;


/**
 * Class TransactionRepositoryAwareTrait
 * @package Finance\Transaction
 */
trait TransactionRepositoryAwareTrait
{


    /**
     * @var TransactionRepositoryInterface
     */
    private $transactionRepository = null;

    public function setTransactionRepository(TransactionRepositoryInterface $repository)
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