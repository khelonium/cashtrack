<?php
use Zend\Mvc\Application;

chdir (dirname(__DIR__));

include 'init_autoloader.php';


class TestBootstrap
{
    /**
     * @var Application
     */
    public static $app = null;

    public static function init()
    {
        if (null == self::$app)
            self::$app = Application::init(include 'config/application.test.config.php');
    }

    public static function get($key)
    {
        self::init();
        return self::$app->getServiceManager()->get($key);
    }
}

TestBootstrap::init();
