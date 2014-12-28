<?php
return  [
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

        'visual' => [
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => [
                'route'    => '/visual',
                'defaults' => [
                    'controller' => 'Application\Controller\Index',
                    'action'     => 'visual',
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

        'overview-rest' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/api/overview[/:id]',
                'constraints' => [
                    'id'     => '[0-9]+[-]?((0)?[1-9]|1[012])?',
                ],

                'defaults' => [
                    'controller' => 'Application\API\Overview',
                ],
            ],
        ],

        'overview-time-rest' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/api/overview/time/:type[/:id]',
                'constraints' => [
                    'id'     => '[0-9]+[-]?((0)?[1-9]|1[012])?',
                    'type'   => 'month|week'
                ],

                'defaults' => [
                    'controller' => 'Application\API\TimeView',
                ],
            ],
        ],

        'breakdown-rest' => [
            'type'    => 'segment',
            'options' => [
                'route'    => '/api/breakdown/:type/:year/:id',
                'constraints' => [
                    'id'     => '[0-9]+[-]?((0)?[1-9]|1[012])?',
                    'type'   => 'month|week'
                ],

                'defaults' => [
                    'controller' => 'Application\API\Breakdown',
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