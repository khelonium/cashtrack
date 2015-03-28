<?php

return array(
    // This should be an array of module namespaces used in the application.
    'modules' => array(
        'Application',
        'Factory',

    ),

    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options' => array(
        'module_paths' => array(
            './apps/web',
            './apps/core',
        ),

        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),

    ),

);
