<?php

$config = include 'application.dev.config.php';

$config['modules'][]=  'ZfcBase';
$config['modules'][]=  'ZfcUser';
$config['modules'][]=  'Auth';

return $config;