<?php

include_once 'bootstrap.php';

$class = new \Jobs\CheckMonthly();

$class->setUp();
$class->perform();