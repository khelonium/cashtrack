<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 12/25/14
 * Time: 9:22 AM
 */

namespace Application\View;


use Zend\View\Model\JsonModel;

class Error extends JsonModel
{

    public function __construct($message)
    {
        parent::__construct(['message' => $message]);
    }
}