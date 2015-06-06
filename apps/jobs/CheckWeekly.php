<?php
namespace Jobs;

class CheckWeekly extends AbstractCheck
{
    const OVERFLOW_KEY_WEEK = "overflow_week";
    const ALMOST_OVERFLOW_WEEK = "almost_overflow_week";


    private $overflow;

    const WEEKLY_LIMIT = 850;

    protected function init()
    {
        $this->overflow = $this->sm->get('Overflow\WeeklyOverflow');
    }


    public function perform()
    {
        if ($this->overflow->isAbove(self::WEEKLY_LIMIT)) {
            $this->sent(self::OVERFLOW_KEY_WEEK) or $this->notifyExcess();
            return;
        }

        if ($this->overflow->isAlmostAbove(self::WEEKLY_LIMIT)) {
            $this->sent(self::ALMOST_OVERFLOW_WEEK) or $this->notifyAlmost();
        }


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

    private function getHeaders()
    {
        return 'From: finance@refactoring.ro' . "\r\n" .
        'Reply-To: no-reply@refactoring.ro' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    }


}