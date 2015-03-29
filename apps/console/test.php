<?php
/**
 *
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */

require_once 'bootstrap.php';


/** @var Database\Account\AccountRepository $accounts */
$accounts = $sm->get('\Database\Account\Repository');

/** @var Adapter $adapter */
$adapter = $sm->get('\Zend\Db\Adapter\Adapter');


//echo Resque::enqueue('finance.watchdog', 'Jobs\CheckMonthly');
echo Resque::enqueue('finance.watchdog', 'Jobs\ResetWatchdog');