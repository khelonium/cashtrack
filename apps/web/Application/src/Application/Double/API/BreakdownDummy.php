<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/7/15
 * Time: 2:40 PM
 */

namespace Application\Double\API;

use Application\API\Breakdown\AbstractBreakdown;

class BreakdownDummy extends AbstractBreakdown
{
    private $year = null;
    private $hasBadRequest = false;
    private $called = false;

    public function yearWillReturn($return)
    {
        $this->year = $return;
    }


    /**
     * @return boolean
     */
    public function hasBadRequest()
    {
        return $this->hasBadRequest;
    }

    public function breakDownIsCalled()
    {
        return $this->called;
    }

    protected function setBadRequest()
    {
        $this->hasBadRequest = true;
    }


    protected function getBreakdown($id)
    {
        $this->called = true;
    }

    protected function getYear()
    {
        return $this->year;
    }



}