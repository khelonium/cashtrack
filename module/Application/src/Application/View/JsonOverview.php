<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 22/09/14
 * Time: 22:20
 */

namespace Application\View;


use Zend\View\Model\JsonModel;

class JsonOverview extends JsonModel
{

    public function __construct($results)
    {
        $out = [];
        $max = 0;

        foreach($results as $result) {
            $out[]= $result;
            if ($result['total'] > $max) {
                $max = $result['total'];
            }
        }

        $format = function ($el) use ($max) {
            $el['radius']  = $el['total'] / $max * 100;
            $el['cluster'] = (int)$el['id_category'];
            $el['name'] = $el['name'].':'.$el['total'];
            $el['cx'] = 100;
            $el['cy'] = 100;
            return $el;
        };

        $out = array_map($format,$out);


        parent::__construct(['nodes' => $out]);
    }
} 