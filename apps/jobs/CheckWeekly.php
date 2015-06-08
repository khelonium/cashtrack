<?php
namespace Jobs;

class CheckWeekly extends AbstractCheck
{
    const OVERFLOW_KEY_WEEK = "overflow_week";
    const ALMOST_OVERFLOW_WEEK = "almost_overflow_week";


    protected $overflow;

    const WEEKLY_LIMIT = 850;

    protected function init()
    {
        $this->overflow = $this->sm->get('Overflow\WeeklyOverflow');
    }


    protected function notifyExcess()
    {
        mail('cosmin.dordea@yahoo.com', "Weekly Limit  Exceeded", "Sent by finance", $this->getHeaders());
        $this->markSent(self::OVERFLOW_KEY_WEEK);
        echo "Exceeded \n";
    }

    protected function notifyAlmost()
    {
        mail('cosmin.dordea@yahoo.com', "Weekly Limit Almost Reached", "Sent by finance", $this->getHeaders());
        $this->markSent(self::ALMOST_OVERFLOW_WEEK);
        echo "Almost exceeded \n";
    }

    /**
     * @return int
     */
    protected function getLimit()
    {
        return self::WEEKLY_LIMIT;
    }

    /**
     * @return bool
     */
    protected function overflowNotificationSent()
    {
        return $this->sent(self::OVERFLOW_KEY_WEEK);
    }

    /**
     * @return bool
     */
    protected function warningNotificationSent()
    {
        return $this->sent(self::ALMOST_OVERFLOW_WEEK);
    }


}