<?php
namespace Jobs;

use Refactoring\Time\Interval\ThisMonth;

abstract class AbstractCheck
{

    public $args = [];
    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $sm = null;

    protected $overflow;


    public function setUp()
    {
        $bootstrap = \Zend\Mvc\Application::init($this->getConfig());
        $this->sm = $bootstrap->getServiceManager();
        $this->init();

    }

    public function perform()
    {
        if ($this->overflow->isAbove($this->getLimit())) {
            $this->overflowNotificationSent() or $this->notifyExcess();
            return;
        }

        if ($this->overflow->isAlmostAbove($this->getLimit())) {
            $this->warningNotificationSent() or $this->notifyAlmost();
        }

    }


    abstract protected function notifyExcess();
    abstract protected function notifyAlmost();
    abstract protected function init();
    abstract protected function overflowNotificationSent();
    abstract protected function warningNotificationSent();
    abstract protected function getLimit();


    protected function sent($key)
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
    protected function redisClient()
    {
        $this->client or $this->client = new \Credis_Client();

        return $this->client;
    }

    /**
     * @param $key
     */
    protected function markSent($key)
    {
        $thisMonth = new ThisMonth();
        $this->redisClient()->set($key, "sent");
        $this->redisClient()->expireAt($key, $thisMonth->getEnd()->getTimestamp());
    }

    /**
     * @return mixed
     */
    protected function getConfig()
    {
        return include 'config/application.config.php';
    }

    protected function getHeaders()
    {
        return 'From: finance@refactoring.ro' . "\r\n" .
        'Reply-To: no-reply@refactoring.ro' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    }
}