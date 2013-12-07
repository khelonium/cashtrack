<?php
/**
 * @author Cosmin Dordea <cosmin.dordea@refactoring.ro>
 */

namespace Finance\Account;

interface AccountFactoryAwareInterface
{
    public function setAccountFactory(AccountFactory $factory);
}