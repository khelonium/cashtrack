<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));
date_default_timezone_set('Europe/Bucharest');//to be moved in ini, testing travis
// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

$env = getenv('APPLICATION_ENV');

$config = '';

switch ($env) {
    case 'development':
        $config = 'application.dev.config.php';
        break;
    case 'testing':
        $config = 'application.test.config.php';
        break;
    default:
        $config = 'application.config.php';
        break;
}

// Run the application!
Zend\Mvc\Application::init(require 'config/'.$config)->run();
