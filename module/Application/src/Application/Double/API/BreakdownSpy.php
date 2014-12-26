<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 12/25/14
 * Time: 9:01 AM
 */
namespace Application\Double\API;

use Application\API\Breakdown;

class BreakdownSpy extends Breakdown
{
    private $requestType;

    private $year = 2014;

    private $badWasSet = false;


    public function setBadRequestWasSet()
    {
        return $this->badWasSet;
    }

    protected function getYear()
    {
        return $this->year;
    }


    protected function setBadRequest()
    {
        $this->badWasSet = true;
    }


    public function yearWillReturn($year)
    {
        $this->year = $year;
    }

    protected function getType()
    {
        return $this->requestType;
    }


    public function typeToReturn($typeToReturn)
    {
        $this->requestType = $typeToReturn;
    }

}