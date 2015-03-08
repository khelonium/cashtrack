<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Refactoring\Time\Interval\IntervalInterface;
use Refactoring\Time\Interval\LastWeek;
use Refactoring\Time\Interval\ThisMonth;
use Refactoring\Time\Interval\ThisWeek;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\View;

class Report extends AbstractActionController
{


    public function weekAction()
    {
        return new ViewModel(
            [
                'week' => $this->params('week'),
                'year' => $this->params('year', date('Y'))
            ]
        );
    }

    public function monthAction()
    {
        return new ViewModel(
            [
                'month' => $this->params('month'),
                'year' => $this->params('year', date('Y'))
            ]
        );
    }


    public function yearAction()
    {
        return new ViewModel(
            [
                'year' => $this->params('unit')
            ]
        );
    }

    public function weeklyAction()
    {

    }


    public function lastWeekAction()
    {
        return $this->getWeekModel(new LastWeek());
    }

    public function thisWeekAction()
    {
        return $this->getWeekModel(new ThisWeek());
    }

    public function thisMonthAction()
    {
        $month = new ThisMonth();
        return new ViewModel(
            [
                'month' => $month->getStart()->format('m'),
                'year' => $month->getStart()->format('y')
            ]
        );
    }


    public function monthlyAction()
    {

    }


    public function yearlyAction()
    {

    }


    /**
     * @param $week
     * @return ViewModel
     */
    protected function getWeekModel(IntervalInterface $week)
    {
        return new ViewModel(
            [
                'week' => $week->getEnd()->format('W'),
                'year' => $week->getEnd()->format('Y')
            ]
        );
    }
}
