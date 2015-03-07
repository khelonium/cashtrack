<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/7/15
 * Time: 2:00 PM
 */

namespace Application\API\Breakdown;

use Zend\View\Model\JsonModel;

class Year extends AbstractBreakdown
{
    public function get($id)
    {
       return new JsonModel($this->getBreakdown($id));
    }

    protected function getBreakdown($id)
    {
        return $this->service->year($id);
    }

}