<?php

require_once 'bootstrap.php';

echo Resque::enqueue('finance.watchdog', 'Jobs\ResetWatchdog');