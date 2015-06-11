<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 1/18/15
 * Time: 9:27 PM
 */

namespace Application\View\Time;



class Month extends Week
{
    const MONTHS = 12;

    protected function getUnitRange()
    {
        return self::MONTHS;
    }


}