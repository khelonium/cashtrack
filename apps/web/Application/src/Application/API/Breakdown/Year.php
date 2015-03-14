<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/7/15
 * Time: 2:00 PM
 */

namespace Application\API\Breakdown;

use Refactoring\Time\Interval\SpecificYear;
use Zend\View\Model\JsonModel;

class Year extends AbstractBreakdown
{
    public function get($id)
    {
        return new JsonModel($this->getBreakdown($id));
    }

    protected function getBreakdown($id)
    {
        return $this->cashflow->expensesFor(new SpecificYear(new \DateTime("$id-01-01")));
    }

}