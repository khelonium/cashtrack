<?php
namespace Jobs;

class ResetWatchdog 
{

    public function perform()
    {
        $client = new \Credis_Client();

        $client->del(CheckMonthly::OVERFLOW_KEY);
        $client->del(CheckMonthly::ALMOST_OVERFLOW_MONTH);

        echo "Watchdog reset \n";
    }

}