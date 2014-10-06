<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Database\Balance;

use Refactoring\Repository\GenericRepository;

trait BalanceRepositoryAwareTrait
{

    /**
     * @var GenericRepository
     */
    private $balanceRepo = null;

    /**
     * @param GenericRepository $repo
     * @return mixed
     */
    public function setBalanceRepository(GenericRepository $repo)
    {
        $this->balanceRepo = $repo;
    }

    /**
     * @return GenericRepository
     * @throws \RuntimeException
     */
    final protected function getBalanceRepository()
    {
        if (null === $this->balanceRepo) {
            throw new \RuntimeException("Balance repository not configured");
        }
        return $this->balanceRepo;
    }
}
