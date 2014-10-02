<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),

            'visual' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/visual',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'visual',
                    ),
                ),
            ),
            'merchants' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/merchants',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'merchant',
                    ),
                ),
            ),

            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),

            'account-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/account[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\API\Account',
                    ),
                ),
            ),

            'overview-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/overview[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+[-]?((0)?[1-9]|1[012])?',
                    ),

                    'defaults' => array(
                        'controller' => 'Application\API\Overview',
                    ),
                ),
            ),


            'merchant-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/merchant[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+?',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\API\Merchant',
                    ),
                ),
            ),


            'month-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/month[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+[-]?((0)?[1-9]|1[012])?',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\API\Month',
                    ),
                ),
            ),

            'transaction-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/transaction[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\API\Transaction',
                    ),
                ),
            ),
            'balance-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/balance[/:id]',
                    'constraints' => array(
                    ),
                    'defaults' => array(
                        'controller' => 'Application\API\Balance',
                    ),
                ),
            ),

            'cashflow-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/cashflow[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\API\CashFlow',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' =>array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\API\Account' => 'Application\API\Account',
            'Application\API\Transaction' => 'Application\API\Transaction',
            'Application\API\Balance' => 'Application\API\Balance',
            'Application\API\CashFlow' => 'Application\API\CashFlow',
            'Application\API\Month' => 'Application\API\Month',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),

    'navigation' => array(
     'default' => array(
         array(
             'label' => 'Cashflow',
             'route' => 'home',
         ),
         array(
             'label' => 'Merchants',
             'route' => 'merchants',
         ),
         array(
             'label' => 'Reports',
             'route' => 'visual',
         ),

     ),
 ),
);
