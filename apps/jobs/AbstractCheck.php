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

    public function setUp()
    {
        $bootstrap = \Zend\Mvc\Application::init($this->getConfig());
        $this->sm = $bootstrap->getServiceManager();

        $this->init();

    }


    abstract protected function init();

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
}