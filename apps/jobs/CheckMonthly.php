<?php
namespace Jobs;

class CheckMonthly 
{

    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    private $sm = null;

    public $args = [];

    public function setUp()
    {

        $bootstrap      = \Zend\Mvc\Application::init(include 'config/application.config.php');
        $this->sm       = $bootstrap->getServiceManager();
        $this->overflow = $this->sm->get('Overflow\MonthlyOverflow');

    }

    public function perform()
    {
        $amount = 19;

        if ($this->overflow->isAbove($amount)) {
            mail('cosmin.dordea@yahoo.com', "Monthly Limit  Exceeded", "Sent by finance");
            return;
        }

        if ($this->overflow->isAlmostAbove($amount)) {
            mail('cosmin.dordea@yahoo.com', "Monthly Limit Almost Reached", "Sent by finance");
        }




    }
}