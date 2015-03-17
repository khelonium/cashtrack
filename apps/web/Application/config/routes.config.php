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



        'weekly' => [
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => [
                'route'    => '/report/weekly',
                'defaults' => [
                    'controller' => 'Application\Controller\Index',
                    'action'     => 'weekly',
                ],
            ],
        ],

        'month' => [
            'type' => 'segment',
            'options' => [
                'route'    => '/report/month/:year/:month',
                'constraints' => [
                    'year'     => '[0-9]+',
                    'month'     => '[0-9]+',
                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                ],
                'defaults' => [
                    'controller' => 'Application\Controller\Report',
                    'action'     => 'month',
                ],
            ],
        ],

        'week' => [
            'type' => 'segment',
            'options' => [
                'route'    => '/report/week/:year/:week',
                'constraints' => [
                    'year'     => '[0-9]+',
                    'week'     => '[0-9]+',
                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                ],
                'defaults' => [
                    'controller' => 'Application\Controller\Report',
                    'action'     => 'week',
                ],
            ],
        ],

        'report' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/report/:action[/:unit]',
                'constraints' => [
                    'unit'     => '[0-9]+',
                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                ],
                'defaults' => [
                    'controller' => 'Application\Controller\Report',
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

        'prediction' => [
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => [
                'route'    => '/prediction',
                'defaults' => [
                    'controller' => 'Application\Controller\Index',
                    'action'     => 'prediction',
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


        'overview-time-rest' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/api/overview/time/:type[/:id]',
                'constraints' => [
                    'id'     => '[0-9]+[-]?((0)?[1-9]|1[012])?',
                    'type'   => 'month|week|year'
                ],

                'defaults' => [
                    'controller' => 'Application\API\TimeView',
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