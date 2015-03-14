<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/7/15
 * Time: 2:02 PM
 */

namespace Application\API\Breakdown;

use Refactoring\Time\Interval\IsoWeek;

class Week extends Month
{

    protected function getBreakdown($id)
    {
        return $this->cashflow->expensesFor(new IsoWeek($this->getYear(), $id));
    }
}
