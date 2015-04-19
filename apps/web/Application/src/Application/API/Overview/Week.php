<?php namespace Application\API\Overview;

use Application\View\Time\Week as WeekView;
use Zend\Mvc\Controller\AbstractRestfulController;

class Week extends AbstractRestfulController
{

    /**
     * @var YearView
     */
    private $reporter = null;

    public function __construct($reporter)
    {
        $this->reporter = $reporter;
    }

    public function get($year)
    {
        return new WeekView($this->reporter->weekly($year));
    }



}