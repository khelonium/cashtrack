<?php
chdir(dirname(dirname(dirname(__DIR__))));

include 'init_autoloader.php';

\Zend\Mvc\Application::init(include 'config/application.config.php');


