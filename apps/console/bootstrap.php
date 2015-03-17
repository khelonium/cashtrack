<?php
/**
 * @author Cosmin Dordea <cosmin.dordea@yahoo.com>
 * @link      http://github.com/khelonium/zentrack
 * @license  New BSD
 */

date_default_timezone_set('Europe/Bucharest');

chdir (dirname(dirname(__DIR__)));


// Setup autoloading
require 'init_autoloader.php';

error_reporting(E_ALL | E_STRICT);


$bootstrap        = \Zend\Mvc\Application::init(include 'config/application.config.php');

$sm = $bootstrap->getServiceManager();



