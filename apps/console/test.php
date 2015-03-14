<?php
/**
 *
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */

require_once 'bootstrap.php';


$import = false;
$service = $sm->get('\Finance\Balance\BalanceService');


$balance =  $service->get('49', new \Refactoring\Interval\SpecificMonth(new DateTime('2013-10-01')));

echo "\n";
echo $balance->getBalance(),"\n";
