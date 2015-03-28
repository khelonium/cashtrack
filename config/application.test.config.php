<?php
return array(
    'modules' => array(
        'Application',
        'Factory',
    ),

    'module_listener_options' => array(
        'module_paths' => array(
            './apps/web',
            './apps/core',
        ),

        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,test}.php',
        ),

    ),

);
