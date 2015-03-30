<?php
namespace Jobs;

class ResetWatchdog 
{

    public function perform()
    {
        $client = new \Credis_Client();

        $client->del(CheckMonthly::OVERFLOW_KEY);
        $client->del(CheckMonthly::ALMOST_OVERFLOW_MONTH);

        $client->del(CheckWeekly::ALMOST_OVERFLOW_WEEK);
        $client->del(CheckWeekly::OVERFLOW_KEY_WEEK);
        echo "Watchdog reset \n";
    }

}