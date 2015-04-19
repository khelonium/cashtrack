<?php namespace Application\API\Overview;

use Reporter\TimeViewInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Application\View\Time\Month as MonthView;

class Month extends AbstractRestfulController
{

    private $reporter = null;

    public function __construct(TimeViewInterface $reporter)
    {
        $this->reporter = $reporter;
    }

    public function get($year)
    {
        return new MonthView($this->reporter->monthly($year));

    }



}