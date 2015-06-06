<?php namespace Jobs\Double;

class MonthCheckDouble extends \Jobs\CheckMonthly
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