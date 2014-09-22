<?php
/**
 *
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */

require_once 'bootstrap.php';


$import = true;
$adapter = $sm->get('\Zend\Db\Adapter\Adapter');



$dir = getcwd().DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'export'.DIRECTORY_SEPARATOR.'2014/bulk2';


echo "\n";


echo "\n";
if ($import) {
    $merchant_service = $sm->get('\Finance\Merchant\Repository');
    $matcher = new \Import\BT\Matcher($merchant_service->all());



    $parser =  new \Import\BT\Parser(new \Import\BT\Matcher($merchant_service->all()));

    $import = new \Import\BT\Importer($sm->get('\Finance\Transaction\Repository'), $parser);


    foreach (new DirectoryIterator($dir) as $element) {
        if ($element->isFile() && $element->getExtension() == 'csv') {
            echo "Importing ".$element,"\n";
            $import->import($element->getPathname());
        }
    }

}

if ($import) {
    return;
}


/** @var \Zend\Db\Adapter\Adapter $adapter */

$report_service = new Report\Account\Service($adapter);


$start = new \DateTime('2013-09-01');
$end   = new \DateTime('2013-09-30');

$result = $report_service->get($start, $end);


$total = null;

foreach ($result as $entry) {
    echo $entry['name'],' ', $entry['amount'],"\n";
    $total += $entry['amount'];

}

echo "Total : $total \n";


echo "\n ------------- \n";

echo "\n ATM \n";
$transaction = $sm->get('Finance\Dao\TransactionGateway');
$atms        = $transaction->select(array("to_account" => 32));


foreach ($atms as $atm) {

    echo $atm->id," ", $atm->date , " ", $atm->description," ",$atm->amount,"\n";
}


echo "\n ------------- \n";

echo "\n Unknown \n";

$unkn        = $transaction->select(array("to_account" => 31));

foreach ($unkn as $tr) {

    echo $tr->id," ", $tr->date , " ", $tr->description," ",$tr->amount,"\n";
}


