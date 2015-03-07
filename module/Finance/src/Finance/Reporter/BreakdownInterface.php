<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 12/25/14
 * Time: 10:42 PM
 */
namespace Finance\Reporter;

interface BreakdownInterface
{
    public function week($year, $week);

    public function month($year, $month);

    public function year($id);
}