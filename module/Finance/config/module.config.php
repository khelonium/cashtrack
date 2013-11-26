<?php
return array(
    'controllers' => array(
        'invokables' => array(
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(

        ),
    ),

    'view_manager' => array( //Add this config
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

);