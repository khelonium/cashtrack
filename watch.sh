#!/bin/bash
INTERVAL=10 QUEUE=finance.watchdog  APP_INCLUDE=apps/jobs/bootstrap.php  php vendor/chrisboulton/php-resque/resque.php 

