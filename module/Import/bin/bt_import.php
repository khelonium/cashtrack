<?php
/**
 *
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */
namespace Import;


chdir(dirname(dirname(dirname(__DIR__))));


// Setup autoloading
require 'init_autoloader.php';

error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('Europe/Bucharest');

$bootstrap  =
    \Zend\Mvc\Application::init(include 'config/application.config.php');

$sm = $bootstrap->getServiceManager();

use Import\BT\Transaction   as Transaction;
use Import\BT\Result  as Result;
use Import\BT\Matcher as Matcher;
use Import\BT\Parser as Parser;

chdir(__DIR__);

$result = new Result();
$parser = new Parser($result);
$parser->parse('RO76BTRL06701202T18825XX-01.06.2013-30.06.2013.csv');
$parser->parse('RO81BTRL06601201514881XX-01.06.2013-30.06.2013.csv');

$matcher = new Matcher();
$matcher->doMagic();


$matches = array (
        'mancare' => array (
                'LIDL',
                'REBEALEX',
                'AUCHAN',
                'ONCOS',
                "VICTOR'S SPICES"

        ),

        'electronice'=> array(
                'eMAG  Bucuresti'
        ),

        'casa' => array(
                'BAUMAX',
        ),

        'bebe' => array (
                'BLUE LIFE MED CENTER',
                'ANDREMIR SRL',
                'ELMAFARM SRL',
                'VITAFARM PLUS SRL',
                'DONA 75',
                'SENSIBLU CLUJ 8'

        ),

        'personal' => array (
                'MARINOPOULOS COFFEE SRL',
                'PANEMAR MORARIT SI PANI',
                'SALATE PANINI',
                'AL.VAIDA VOIEVOD  nr. 53-55',
                'TYPEIT4ME',
                'AUDIBLE.CO.UK'
        ),


        'carti' => array (
                'Amazon Services-Kindle'
        ),

        'IT' => array(
                'LINODE.COM',
                'TEXTBROKER'
        ),

        'delegatie' => array(
                'CIOCANA'
        ),

        'Orange' => array(
                'RO 52990213',
                'ORANGE WEBCARE'

        ),

        'Rata BT' => array(
                'Practic BT',
                'Rambursare principal credit',

        ),

        'Internet' => array(
                'DIGICARE.RCS-RDS.RO'
        ),
        'Electrica' => array (
                'ELECTRICA'
        ),
         
        'farmaceutice' => array (
        ),

        'Taxe Banca' => array (
                'Comision procesare',
                'Nota corectie',
                'Comision',
                'Abonament BT 24'
        ),

);

$expense_service = $sm->get('ExpenseService');


foreach ($matches as $expense => $merchants) {
    $expense_id = addExpense($expense);
    foreach ($merchants as $merchant) {
        addMerchant($merchant, $expense_id);
    }
    
}



function addExpense($expense) {
    global $expense_service;
    $expense_service->addExpense($expense);
    echo "Adding expense $expense \n";
    
    return $expense->id;
}

function addMerchant($name ,$id)
{
    echo "Adding merchant $name to $id \n";
}

die();
$categories = array_flip($matcher->getCategories());

foreach ($categories as $key => &$val) {
    $val = 0;
}

$unmatched = array();
$atm       = array();

foreach ($result as $entry) {
    $matcher->match($entry);
    
    if ($entry->isATM()) {
        $atm[] = $entry;
    }
     
}


/**
 * @var \Finance\Transaction\Mapper
 */
$mapper = $sm->get('\Finance\Transaction\Mapper');



foreach ($result as $entry) {
    
    if ($entry->hasMeta('category')) {
        $categories[$entry->getMeta('category')] += $entry->debit;

    } else {
        if($entry->isExpense()) {
            $unmatched[] = $entry;
        }
    }
}


return;
echo "\n\n";
echo "UNMATCHED ENTRIES","\n";


array_walk($unmatched, 'display');
echo "\n\n";
echo "ATM Withdrawals \n";
array_walk($atm, 'display');

function display($entry) {
    echo $entry,"\n";
}


foreach ($atm as $entry) {
    echo "RETRAGERE\t",$entry->transactionDate,"\t", -1 * $entry->debit,"\n";
}
foreach ($categories as $category => $sum ) {
    $sum = $sum * -1;
    echo "$category \t $sum \n";
}
