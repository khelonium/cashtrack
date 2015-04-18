<?php

include_once 'bootstrap.php';

$class = new \Jobs\CheckWeekly();

$class->setUp();
$class->perform();