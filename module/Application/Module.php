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
        $app = $e->getApplication();
        $em  = $app->getEventManager();
        $em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'selectLayoutBasedOnRoute'));

        //стандартный роут слушатель
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        //событие проверки авторизации на роутах
        $eventManager->attach("dispatch", function($e) {

            $match = $e->getRouteMatch();
            //если роут логин, то не вешать
            if (0 === strpos($match->getMatchedRouteName(), 'login')) {
                return;
            }

            $app     = $e->getApplication();
            $request = $app->getRequest();

            $currentUrl = $request->getUri()->getPath();
            //не вешаем, если в пути нету Admin
            if (FALSE === strpos($currentUrl, 'admin')) {
                return;
            }

            if (!$app->getServiceManager()
                            ->get('AuthService')->hasIdentity()) {

                $response = $this->formNotAuthResponse($e);
                $this->setBreakEvent($e, $response);
            }
        });

        //поднять сессию
        $this->bootstrapSession($e);
    }

    /**
     * ответ об ошибке и редирект
     * @param type $e
     * @return type
     */
    private function formNotAuthResponse($e)
    {
        $url = $e->getRouter()
                ->assemble(array(), array('name' => 'login/login'));

        $response = $e->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $url);
        $response->setStatusCode(302);
        $response->sendHeaders();

        return $response;
    }

    /**
     * остановить обработку дальше
     * @param type $e
     * @param type $response
     * @param type $e
     * @return type
     */
    private function setBreakEvent($e, $response)
    {
        // When an MvcEvent Listener returns a Response object,
        // It automatically short-circuit the Application running
        // -> true only for Route Event propagation see Zend\Mvc\Application::run
        // To avoid additional processing
        // we can attach a listener for Event Route with a high priority
        $stopCallBack = function($e) use ($response) {
            $e->stopPropagation();
            return $response;
        };

        //Attach the "break" as a listener with a high priority
        $e->getApplication()
                ->getEventManager()
                ->attach(MvcEvent::EVENT_ROUTE, $stopCallBack, -10000);

        return $response;
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

        if (!($match instanceof RouteMatch) ||
                !in_array($match->getMatchedRouteName(), $config['adminPath']['routes']) ||
//                0 !== strpos($match->getMatchedRouteName(), 'admin') ||
                $controller->getEvent()->getResult()->terminate()
        ) {
            return;
        }

        $layout = $config['adminPath']['admin_layout_template'];
        $controller->layout($layout);
    }

    /**
     * создать сессию при старте
     * @param \Zend\Mvc\MvcEvent $e
     */
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
                //инициализация менеджера сессии
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

            //установка для контейнеров
            Container::setDefaultManager($sessionManager);

            return $sessionManager;
        },
            ),
        );
    }

}
