<?php namespace Application\API\Overview;

use Application\View\Time\Year as YearView;
use Database\Reporter\TimeMaster;
use Reporter\TimeViewInterface;
use Zend\Mvc\Controller\AbstractRestfulController;

class Year extends AbstractRestfulController
{

    /**
     * @var TimeViewInterface
     */
    private $reporter = null;

    public function __construct($reporter)
    {
        $this->reporter = $reporter;
    }

    public function get($year)
    {
        return new YearView($this->reporter->yearly());
    }



}