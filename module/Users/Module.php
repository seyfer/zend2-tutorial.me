<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Users;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Users\Model;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class Module implements AutoloaderProviderInterface
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // if we're in a namespace deeper than one level we need to fix the \ in the path
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

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $sharedEventManager = $eventManager->getSharedManager();
        // менеджер событий
        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, function($e) {
            $controller     = $e->getTarget(); // обслуживаемый контроллер
            $controllerName = $controller->getEvent()
                            ->getRouteMatch()->getParam('controller');

            if (!in_array($controllerName, array(
                        'Users\Controller\Index', 'Users\Controller\Register',
                        'Users\Controller\Login'))) {
                $controller->layout('layout/admin');
            }
        });
    }

    public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(),
            'aliases'            => array(),
            'factories'          => array(
                // база данных
                'UserTable' => function($sm) {
            $tableGateway = $sm->get('UserTableGateway');
            $table        = new Model\UserTable($tableGateway);
            return $table;
        },
                'UserTableGateway'   => function ($sm) {
            $dbAdapter          = $sm->get('Zend\Db\Adapter\Adapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Model\User());
            return new TableGateway('myuser', $dbAdapter, null, $resultSetPrototype);
        },
                'UploadTable' => function($sm) {
            $tableGateway              = $sm->get('UploadTableGateway');
            $uploadSharingTableGateway = $sm->get('UploadSharingTableGateway');
            $table                     = new Model\UploadTable($tableGateway, $uploadSharingTableGateway);
            return $table;
        },
                'UploadTableGateway' => function ($sm) {
            $dbAdapter          = $sm->get('Zend\Db\Adapter\Adapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Model\Upload());
            return new TableGateway('uploads', $dbAdapter, null, $resultSetPrototype);
        },
                'ImageUploadTable' => function($sm) {
            $tableGateway = $sm->get('ImageUploadTableGateway');
            $table        = new Model\ImageUploadTable($tableGateway);
            return $table;
        },
                'ImageUploadTableGateway' => function ($sm) {
            $dbAdapter          = $sm->get('Zend\Db\Adapter\Adapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Model\ImageUpload());
            return new TableGateway('image_uploads', $dbAdapter, null, $resultSetPrototype);
        },
                // Формы
                'LoginForm' => function ($sm) {
            $form = new \Users\Form\LoginForm();
            $form->setInputFilter($sm->get('LoginFilter'));
            return $form;
        },
                'RegisterForm' => function ($sm) {
            $form = new \Users\Form\RegisterForm();
            $form->setInputFilter($sm->get('RegisterFilter'));
            return $form;
        },
                'UserEditForm' => function ($sm) {
            $form = new \Users\Form\UserEditForm();
            return $form;
        },
                // Фильтры
                'LoginFilter' => function ($sm) {
            return new \Users\Form\Filter\LoginFilter();
        },
                'RegisterFilter' => function ($sm) {
            return new \Users\Form\Filter\RegisterFilter();
        },
                'UploadForm' => function($sm) {
            $form        = new Form\UploadForm();
            $uploadTable = $sm->get('UploadTable');
            $userTable   = $sm->get('UserTable');
            $form->setUploadTable($uploadTable);
            $form->setUserTable($userTable);

            return $form;
        },
                'SendMailForm' => function($sm) {
            $form      = new Form\SendMailForm();
            $userTable = $sm->get('UserTable');
            $form->setUserTable($userTable);

            return $form;
        },
                'AuthStorageUsers' => function ($sm) {
            return new Model\AuthStorage();
        },
                'AuthServiceUsers' => function ($sm) {
            $dbAdapter          = $sm->get('Zend\Db\Adapter\Adapter');
            $dbTableAuthAdapter = new DbTableAuthAdapter(
                    $dbAdapter, 'myuser', 'email', 'password', 'MD5(?)');
            $authService        = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            $authService->setStorage($sm->get('AuthStorageUsers'));

            return $authService;
        },
                'UploadSharingTableGateway' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            return new TableGateway('uploads_sharing', $dbAdapter);
        },
                'ChatMessagesTableGateway' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            return new TableGateway('chat_messages', $dbAdapter);
        },
            ),
            'invokables' => array(),
            'services'   => array(),
            'shared'     => array(),
        );
    }

}
