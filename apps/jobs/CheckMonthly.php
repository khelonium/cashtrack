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
        $this->mailer->subject("Monthly Limit Exceeded")->send();
        $this->markSent(self::OVERFLOW_KEY);
    }

    protected function notifyAlmost()
    {
        $this->mailer->subject("Monthly Limit  Almost Reached")->send();
        $this->markSent(self::ALMOST_OVERFLOW_MONTH);
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
