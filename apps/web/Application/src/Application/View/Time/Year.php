<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 3/7/15
 * Time: 4:34 PM
 */

namespace Application\View\Time;


use Zend\View\Model\JsonModel;

class Year extends JsonModel
{

    public function __construct($data)
    {
        $data = array_map(
            function($el) {
                return (object) $el;
            },
            $data
        );

        $data = array_filter($data, function ($element) {
            return $element->unit_nr != "0";
        });


        array_walk($data, function($element) {
            $element->amount = round($element->amount, 2);
            $element->unit_nr =  (int) $element->unit_nr ;
        });


        parent::__construct(array_values($data));
    }
}