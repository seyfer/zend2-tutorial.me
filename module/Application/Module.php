<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\Mvc\Router\Http\RouteMatch;

class Module {

    public function onBootstrap(MvcEvent $e)
    {
        //разные layout по роуту
        $app = $e->getParam('application');
        $em  = $app->getEventManager();
        $em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'selectLayoutBasedOnRoute'));

        //стандартный роут слушатель
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        //поднять сессию
        $this->bootstrapSession($e);
    }

    /**
     * Select the admin layout based on route name
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function selectLayoutBasedOnRoute(MvcEvent $e)
    {
        $app    = $e->getParam('application');
        $sm     = $app->getServiceManager();
        $config = $sm->get('config');

        $match      = $e->getRouteMatch();
        $controller = $e->getTarget();

        \Zend\Debug\Debug::dump($match);
        \Zend\Debug\Debug::dump(strpos($match->getMatchedRouteName(), 'admin'));
        \Zend\Debug\Debug::dump($match instanceof RouteMatch);

        if (!($match instanceof RouteMatch) ||
                0 !== strpos($match->getMatchedRouteName(), 'admin') ||
                $controller->getEvent()->getResult()->terminate()
        ) {
            return;
        }

        $layout = $config['admin']['admin_layout_template'];
        $controller->layout($layout);
    }

    public function bootstrapSession(MvcEvent $e)
    {
        $session = $e->getApplication()
                ->getServiceManager()
                ->get('Zend\Session\SessionManager');
        $session->start();

        $container = new Container('initialized');
        if (!isset($container->init)) {
            $session->regenerateId(true);
            $container->init = 1;
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zend\Session\SessionManager' => function ($sm) {

            $config = $sm->get('config');
            if (isset($config['session'])) {
                $session = $config['session'];

                $sessionConfig = null;
                if (isset($session['config'])) {
                    $class = isset($session['config']['class']) ?
                            $session['config']['class'] : 'Zend\Session\Config\SessionConfig';

                    $options = isset($session['config']['options']) ?
                            $session['config']['options'] : array();

                    $sessionConfig = new $class();
                    $sessionConfig->setOptions($options);
                }

                $sessionStorage = null;
                if (isset($session['storage'])) {
                    $class          = $session['storage'];
                    $sessionStorage = new $class();
                }

                $sessionSaveHandler = null;
                if (isset($session['save_handler'])) {
                    // class should be fetched from service manager since
                    // it will require constructor arguments
                    $sessionSaveHandler = $sm->get($session['save_handler']);
                }

                $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);

                if (isset($session['validators'])) {
                    $chain = $sessionManager->getValidatorChain();

                    foreach ($session['validators'] as $validator) {
                        $validator = new $validator();
                        $chain->attach('session.validate', array($validator, 'isValid'));
                    }
                }
            }
            else {
                $sessionManager = new SessionManager();
            }

            Container::setDefaultManager($sessionManager);

            return $sessionManager;
        },
            ),
        );
    }

}
