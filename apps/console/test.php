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

foreach ($accounts->getByType('expense') as $account) {

    $accountBalance = new \Database\Account\AccountSum($account);
    $accountBalance->setDbAdapter($adapter);
    $prediction = new \Prediction\PredictAccount($accountBalance);
    $amount = $prediction->thisMonth();

    if ($amount) {
        echo $account->name,"\t\t\t",round($amount,2),"\n";
    }
}