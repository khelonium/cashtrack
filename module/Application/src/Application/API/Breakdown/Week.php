<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/7/15
 * Time: 2:02 PM
 */

namespace Application\API\Breakdown;

class Week extends Month
{

    protected function getBreakdown($id)
    {
        return $this->service->week($this->getYear(), $id);
    }
}
