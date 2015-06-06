<?php
namespace Jobs\Double;

use Jobs\CheckWeekly;

class WeekCheckDouble extends CheckWeekly
{

    public $excessNotified = false;
    public $almostNotified = false;

    protected function sent($key)
    {
        return false;
    }

    protected function notifyExcess()
    {
        $this->excessNotified = true;
    }

    protected function notifyAlmost()
    {
        $this->almostNotified = true;
    }


    protected function getConfig()
    {
        return include 'config/application.test.config.php';
    }


}