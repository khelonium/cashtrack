<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logo
 * Date: 11/10/13
 * Time: 8:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\API;



use Application\View\Time\Week as WeekView;
use Application\View\Time\Month as MonthView;
use Application\View\Time\Year as YearView;
use Reporter\TimeReporterInterface;

class TimeView extends AbstractController
{


    /**
     * @var TimeReporterInterface
     */
    private $reporter = null;

    public function __construct($reporter)
    {
        $this->reporter = $reporter;
    }

    public function get($period)
    {
        $method = 'get' . ucfirst($this->getType());

        return $this->$method($period);

    }

    public function getMonth($period)
    {
        return new MonthView($this->reporter->monthTotals($period));
    }

    public function getWeek($period)
    {
        return new WeekView($this->reporter->weekTotals($period));

    }

    public function getYear()
    {
        return new YearView($this->reporter->yearTotals());
    }

    private function getType()
    {
        return $this->params('type', 'week');
    }


}