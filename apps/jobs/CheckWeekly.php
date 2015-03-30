<?php
namespace Jobs;

class CheckWeekly extends AbstractCheck
{
    const OVERFLOW_KEY = "overflow_week";
    const ALMOST_OVERFLOW_MONTH = "almost_overflow_week";


    private $overflow;

    protected function init()
    {
        $this->overflow = $this->sm->get('Overflow\WeeklyOverflow');
    }


    public function perform()
    {
        $amount = 850;

        if ($this->overflow->isAbove($amount)) {
            $this->sent(self::OVERFLOW_KEY) or $this->notifyExcess();
            return;
        }

        if ($this->overflow->isAlmostAbove($amount)) {
            $this->sent(self::ALMOST_OVERFLOW_MONTH) or $this->notifyAlmost();
        }

    }

    private function notifyExcess()
    {
        mail('cosmin.dordea@yahoo.com', "Weekly Limit  Exceeded", "Sent by finance", $this->getHeaders());
        $this->markSent(self::OVERFLOW_KEY);
        echo "Exceeded \n";
    }

    private function notifyAlmost()
    {
        mail('cosmin.dordea@yahoo.com', "Weekly Limit Almost Reached", "Sent by finance", $this->getHeaders());
        $this->markSent(self::ALMOST_OVERFLOW_MONTH);
        echo "Almost exceeded \n";
    }

    private function getHeaders()
    {
        return 'From: finance@refactoring.ro' . "\r\n" .
        'Reply-To: no-reply@refactoring.ro' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    }


}