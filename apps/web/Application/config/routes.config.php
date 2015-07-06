<?php
return [
    'routes' => [
        'home' => [
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => [
                'route'    => '/',
                'defaults' => [
                    'controller' => 'Application\Controller\Index',
                    'action'     => 'index',
                ],
            ],
        ],




        'merchants' => [
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => [
                'route'    => '/merchants',
                'defaults' => [
                    'controller' => 'Application\Controller\Index',
                    'action'     => 'merchant',
                ],
            ],
        ],


        'import' => [
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => [
                'route'    => '/import',
                'defaults' => [
                    'controller' => 'Application\Controller\Index',
                    'action'     => 'import',
                ],
            ],
        ],

        'importDone' => [
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => [
                'route'    => '/import/done',
                'defaults' => [
                    'controller' => 'Application\Controller\Index',
                    'action'     => 'done',
                ],
            ],
        ],

        'processImport' => [
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => [
                'route'    => '/import/process',
                'defaults' => [
                    'controller' => 'Application\Controller\Index',
                    'action'     => 'process',
                ],
            ],
        ],


        'application' => array(
            'type'    => 'Literal',
            'options' => [
                'route'    => '/application',
                'defaults' => [
                    '__NAMESPACE__' => 'Application\Controller',
                    'controller'    => 'Index',
                    'action'        => 'index',
                ],
            ],
            'may_terminate' => true,
            'child_routes' => [
                'default' => [
                    'type'    => 'Segment',
                    'options' => [
                        'route'    => '/[:controller[/:action]]',
                        'constraints' => [
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ],
                        'defaults' => [
                        ],
                    ],
                ],
            ],
        ),

        'account-rest' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/api/account[/:id]',
                'constraints' => [
                    'id'     => '[0-9]+',
                ],
                'defaults' => [
                    'controller' => 'Application\API\Account',
                ],
            ],
        ],




        'overview-year-rest' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/api/overview/time/year[/:id]',
                'constraints' => [
                    'id'     => '[0-9]+[-]?((0)?[1-9]|1[012])?'
                ],

                'defaults' => [
                    'controller' => 'Application\API\Overview\Year',
                ],
            ],
        ],

        'overview-month-rest' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/api/overview/time/month[/:id]',
                'constraints' => [
                    'id'     => '[0-9]+[-]?((0)?[1-9]|1[012])?'
                ],

                'defaults' => [
                    'controller' => 'Application\API\Overview\Month',
                ],
            ],
        ],

        'overview-month-week' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/api/overview/time/week[/:id]',
                'constraints' => [
                    'id'     => '[0-9]+[-]?((0)?[1-9]|1[012])?'
                ],

                'defaults' => [
                    'controller' => 'Application\API\Overview\Week',
                ],
            ],
        ],


        'breakdown-rest-month' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/api/breakdown/month/:year/:id',
                'constraints' => [
                    'id'     => '[0-9]+[-]?((0)?[1-9]|1[012])?',
                ],

                'defaults' => [
                    'controller' => 'Application\API\Breakdown\Month',
                ],
            ],
        ],

        'breakdown-rest-week' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/api/breakdown/week/:year/:id',
                'constraints' => [
                    'id'     => '[0-9]+[-]?((0)?[1-9]|1[012])?',
                ],

                'defaults' => [
                    'controller' => 'Application\API\Breakdown\Week',
                ],
            ],
        ],

        'breakdown-rest-year' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/api/breakdown/year/:id',
                'constraints' => [
                    'id'     => '[0-9]+[-]?((0)?[1-9]|1[012])?',
                ],

                'defaults' => [
                    'controller' => 'Application\API\Breakdown\Year',
                ],
            ],
        ],


        'merchant-rest' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/api/merchant[/:id]',
                'constraints' => array(
                    'id'     => '[0-9]+?',
                ),
                'defaults' => [
                    'controller' => 'Application\API\Merchant',
                ],
            ],
        ],



        'transaction-rest' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/api/transaction[/:id]',
                'constraints' => [
                    'id'     => '[0-9]+',
                ],
                'defaults' => [
                    'controller' => 'Application\API\Transaction',
                ],
            ],
        ],

        'cashflow-rest' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/api/cashflow[/:id]',
                'constraints' => [
                    'id'     => '[0-9]+',
                ],
                'defaults' => [
                    'controller' => 'Application\API\CashFlow',
                ],
            ],
        ],
    ],
];