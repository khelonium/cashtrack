<?php
namespace Jobs;

class CheckMonthly extends AbstractCheck
{
    const OVERFLOW_KEY = "overflow_month";
    const ALMOST_OVERFLOW_MONTH = "almost_overflow_month";

    const MONTH_LIMIT = 3500;

    protected function init()
    {
        $this->overflow = $this->sm->get('Overflow\MonthlyOverflow');
    }

    protected function notifyExcess()
    {
        mail('cosmin.dordea@yahoo.com', "Monthly Limit  Exceeded", "Sent by finance", $this->getHeaders());
        $this->markSent(self::OVERFLOW_KEY);
        echo "Exceeded \n";
    }

    protected function notifyAlmost()
    {
        mail('cosmin.dordea@yahoo.com', "Monthly Limit Almost Reached", "Sent by finance", $this->getHeaders());
        $this->markSent(self::ALMOST_OVERFLOW_MONTH);
        echo "Almost exceeded \n";
    }



    /**
     * @return int
     */
    protected function getLimit()
    {
        return self::MONTH_LIMIT;
    }

    /**
     * @return bool
     */
    protected function overflowNotificationSent()
    {
        return $this->sent(self::OVERFLOW_KEY);
    }

    /**
     * @return bool
     */
    protected function warningNotificationSent()
    {
        return $this->sent(self::ALMOST_OVERFLOW_MONTH);
    }


}