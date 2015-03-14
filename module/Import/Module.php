<?php
namespace Import;


use Import\BT\Matcher;
use Import\BT\Parser;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(

            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }


    public function getServiceConfig()
    {
        return array(

            'factories' => array(
                '\Import\BT\Matcher' => function ($sm) {
                    return new Matcher($sm->get('\Database\Merchant\Repository')->all());
                },

                '\Import\BT\Parser' => function ($sm) {
                    return new Parser($sm->get('\Import\BT\Matcher'));
                },


            ),
        );
    }
}
