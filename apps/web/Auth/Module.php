<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Auth;

use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\RouteMatch;

class Module
{

    protected $whiteList = array('zfcuser/login');

    public function onBootstrap(MvcEvent $e)
    {

        $app = $e->getApplication();
        $em  = $app->getEventManager();
        $sm  = $app->getServiceManager();

        $list = $this->whiteList;
        $auth = $sm->get('zfcuser_auth_service');

        $em->attach(MvcEvent::EVENT_ROUTE, function($e) use ($list, $auth) {
            $match = $e->getRouteMatch();

            // No route match, this is a 404
            if (!$match instanceof RouteMatch) {
                return;
            }

            // Route is whitelisted
            $name = $match->getMatchedRouteName();
            if (in_array($name, $list)) {
                return;
            }

            // User is authenticated
            if ($auth->hasIdentity()) {
                return;
            }

            // Redirect to the user login page, as an example
            $router   = $e->getRouter();
            $url      = $router->assemble(array(), array(
                'name' => 'zfcuser/login'
            ));

            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);

            return $response;
        }, -100);
    }


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

    public function init(ModuleManager $moduleManager)
    {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvents->attach('ZfcUser', 'dispatch', function($e) {
            // This event will only be fired when an ActionController under the ZfcUser namespace is dispatched.
            $controller = $e->getTarget();
            $controller->layout('layout/alternative');
        }, 100);
    }

}
