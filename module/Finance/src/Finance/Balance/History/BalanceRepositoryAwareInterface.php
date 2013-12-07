<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Balance\History;

use Refactoring\Repository\GenericRepository;

interface BalanceRepositoryAwareInterface
{

    /**
     * @param GenericRepository $repo
     * @return mixed
     */
    public function setBalanceRepository(GenericRepository $repo);
}
