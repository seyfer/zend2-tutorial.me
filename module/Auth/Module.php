<?php

namespace Auth;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\Authentication\Storage,
    Zend\Authentication\AuthenticationService,
    Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Auth\Model\GateAdapter;

/**
 * Description of Module
 *
 * @author seyfer
 */
class Module implements AutoloaderProviderInterface {

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // if we're in a namespace deeper than one level
                    // we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' .
                    str_replace('\\', '/', __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories'   => array(
                'Auth\Model\AuthStorage' => function($sm) {
            return new \Auth\Model\AuthStorage('auth_storage');
        },
                'AuthService' => function($sm) {
            //My assumption, you've alredy set dbAdapter
            //and has users table with columns : user_name and pass_word
            //that password hashed with md5
//            $dbAdapter          = $sm->get('Zend\Db\Adapter\Adapter');
//            $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'myuser', 'login', 'password', 'MD5(?)');
//
//            $authService = new AuthenticationService();
//            $authService->setAdapter($dbTableAuthAdapter);
//            $authService->setStorage($sm->get('Auth\Model\AuthStorage'));

            $gateAdapter = new GateAdapter();

            $authService = new AuthenticationService();
            $authService->setAdapter($gateAdapter);
            $authService->setStorage($sm->get('Auth\Model\AuthStorage'));

            return $authService;
        },
            ),
        );
    }

}
