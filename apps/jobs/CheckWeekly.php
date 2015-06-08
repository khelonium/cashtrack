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
        $this->mailer->subject("Weekly limit exceeded")->send();
        $this->markSent(self::OVERFLOW_KEY_WEEK);
    }

    protected function notifyAlmost()
    {
        $this->mailer->subject("Weekly limit Almost Reached")->send();
        $this->markSent(self::ALMOST_OVERFLOW_WEEK);
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