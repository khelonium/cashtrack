<?php
namespace Jobs;

use Refactoring\Time\Interval\ThisMonth;

class CheckMonthly
{
    const OVERFLOW_KEY = "overflow_month";
    const ALMOST_OVERFLOW_MONTH = "almost_overflow_month";

    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    private $sm = null;

    public $args = [];

    private $overflow;

    public function setUp()
    {

        $bootstrap      = \Zend\Mvc\Application::init(include 'config/application.config.php');
        $this->sm       = $bootstrap->getServiceManager();
        $this->overflow = $this->sm->get('Overflow\MonthlyOverflow');

    }

    public function perform()
    {
        $amount = 3500;

        if ($this->overflow->isAbove($amount)) {
            $this->sent(self::OVERFLOW_KEY) or $this->notifyExcess();
            return;
        }

        if ($this->overflow->isAlmostAbove($amount)) {
            $this->sent(self::ALMOST_OVERFLOW_MONTH) or $this->notifyAlmost();
        }

    }

    private function sent($key)
    {
        $sent = false !== $this->redisClient()->get($key);

        if ($sent) {
            echo "$key was set in past \n";
        }

        return $sent;
    }

    private $client = null;

    /**
     * @return \Credis_Client
     */
    private function redisClient()
    {
        $this->client or $this->client = new \Credis_Client();

        return $this->client;
    }

    /**
     * @param $key
     */
    private function markSent($key)
    {
        $thisMonth = new ThisMonth();
        $this->redisClient()->set($key, "sent");
        $this->redisClient()->expireAt($key, $thisMonth->getEnd()->getTimestamp());
    }

    private function notifyExcess()
    {
        mail('cosmin.dordea@yahoo.com', "Monthly Limit  Exceeded", "Sent by finance", $this->getHeaders());
        $this->markSent(self::OVERFLOW_KEY);
        echo "Exceeded \n";
    }

    private function notifyAlmost()
    {
        mail('cosmin.dordea@yahoo.com', "Monthly Limit Almost Reached", "Sent by finance", $this->getHeaders());
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