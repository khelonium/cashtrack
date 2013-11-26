<?php
/**
 *
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */

chdir(dirname(dirname(dirname(__DIR__))));


// Setup autoloading
require 'init_autoloader.php';

error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('Europe/Bucharest');

$bootstrap  =
    \Zend\Mvc\Application::init(include 'config/application.config.php');

$sm = $bootstrap->getServiceManager();

$file = fopen('/tmp/expensemanager.csv', 'r');

/**
  0 => 'Date',
  1 => 'Amount',
  2 => 'Category',
  3 => 'Subcategory',
  4 => 'Payment Method',
  5 => 'Description',
  6 => 'Ref/Check No',
  7 => 'Payee/Payer',
  8 => 'Status',
  9 => 'Receipt Picture',
  10 => 'Account',
*/

$persistence = $sm->get('EntryPersistence');

/**
 * @var \Finance\Category\CategoryTable
 */
$category_manager = $sm->get('Finance\Category\CategoryTable');

$date = fgetcsv($file);

/**
 * @param $name
 * @param $parentId
 * @return Finance\Category
 */
function createCategory($name, $parentId)
{
    $category = new \Finance\Category();
    $category->name = $name;
    $category->parent = $parentId;
    return $category;
}

$add =
    function ($categoryName, $amount, $parentId = null) use ($category_manager)
    {
    if ($category_manager->exists($categoryName)) {
        $category = $category_manager->getByName($categoryName);

    } else {

        $category = createCategory($categoryName, $parentId);
        $category->type = ($amount >0)?'income':'expense';
        $category_manager->saveCategory($category);
    }


    return $category;
    };

while ($data = fgetcsv($file)) {
   $entry = new \Finance\Entry();
   $entry['created']     = $data[0];
   $entry['amount']      = $data[1];
   $entry['description'] = $data[5];
   $parent = $add($data[2],$entry['amount']);

   $entry['categoryId'] = $add(
       $data[3], (float)$entry['amount'], $parent->id
   )->id;

    $entry['amount'] = ($entry['amount'] > 0) ? $entry['amount']:
        $entry['amount']*-1;

    try {
        $persistence->add($entry);
    } catch (Exception $e) {
    }

}

