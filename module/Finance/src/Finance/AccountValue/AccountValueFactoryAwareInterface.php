<?php
/**
 * 
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\AccountValue;


/**
 * Class AccountValueFactoryAwareInterface
 * implement it to ease DI
 * @package Finance\AccountValue
 */
interface AccountValueFactoryAwareInterface
{
    /**
     * @param AccountValueFactoryInterface $factory
     * @return null
     */
    public function setAccountValueFactory(AccountValueFactoryInterface $factory);
}