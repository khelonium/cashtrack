<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 23/11/14
 * Time: 16:16
 */
namespace Reporter;

interface TimeViewInterface
{
    /**
     * @param $year
     * @return array
     */
    public function weekly($year);

    public function monthly($year);

    public function yearly();

}
