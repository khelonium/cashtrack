<?php namespace Database\Reporter;

use Reporter\TimeViewInterface;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterAwareTrait;

class TimeMaster implements TimeViewInterface, AdapterAwareInterface
{

    use AdapterAwareTrait;

    public function yearly()
    {
        return $this->build('year')->totals();
    }

    /**
     * @param $year
     * @return array
     */
    public function weekly($year)
    {
        return $this->build('week')->totals($year);


    }
    public function monthly($year)
    {
        return $this->build('month')->totals($year);

    }

    private function build($period)
    {

        switch ($period) {
            case 'month':
                $implementation = new MonthTotals();
                break;
            case 'week':
                $implementation = new WeekTotals();
                break;
            case 'year':
                $implementation = new YearTotals();
                break;
            default:
                throw new \DomainException("No such interval $period");
                break;
        }

        $implementation->setDbAdapter($this->adapter);

        return $implementation;
    }

}
