<?php
/**
 *
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */

require_once 'bootstrap.php';


$import = true;
$adapter = $sm->get('\Zend\Db\Adapter\Adapter');



$dir = getcwd().DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'export'.DIRECTORY_SEPARATOR.'2015/3';


echo "\n";


/** @var \Import\BT\Parser $parser */
$parser  = $sm->get('\Import\BT\Parser');

foreach (new DirectoryIterator($dir) as $element) {
    if ($element->isFile() && $element->getExtension() == 'csv') {
        echo "Importing ".$element,"\n";
        $transactions = $parser->parse($element->getPathname());
        /** @var \Import\BT\Transaction $transaction */
        foreach ($transactions as $transaction) {
            if ($transaction->toAccount == 31) {
                echo $transaction->getAmount(), " ", $transaction->getDescription(), "\n";
            }
        }

    }
}

