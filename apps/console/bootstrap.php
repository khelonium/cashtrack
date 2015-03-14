<?php
/**
 * @author Cosmin Dordea <cosmin.dordea@yahoo.com>
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */

date_default_timezone_set('Europe/Bucharest');

chdir (dirname(dirname(__DIR__)));

echo dirname(dirname(dirname(__DIR__)));

//SELECT year(currency_date) as `year`, month(currency_date) as `month` , currency.name , avg(value) as value FROM `currency_rate` LEFT JOIN currency on currency.id_currency = currency_rate.id_currency    group by `year`,`month`  , currency.name

//SELECT year(currency_date) as `year`, month(currency_date) as `month` , currency.name , avg(value) as value FROM `currency_rate` LEFT JOIN currency on currency.id_currency = currency_rate.id_currency group by `year`,`month`
// Setup autoloading
require 'init_autoloader.php';

error_reporting(E_ALL | E_STRICT);


$bootstrap        = \Zend\Mvc\Application::init(include 'config/application.config.php');

$sm = $bootstrap->getServiceManager();



