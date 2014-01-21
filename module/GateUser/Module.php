<?php

namespace GateUser;

use ZfcUser\Module as ZfcUser;

class Module {

    public function onBootstrap($e)
    {

    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
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
            'aliases'   => array(
                'zfcuser_doctrine_em' => 'doctrine.entitymanager.orm_default',
            ),
            'factories' => array(
//                'zfcuser_module_options' => function ($sm) {
//            $config = $sm->get('Configuration');
//            return new Options\ModuleOptions(isset($config['zfcuser']) ? $config['zfcuser'] : array());
//        },
                'zfcuser_user_mapper' => function ($sm) {

            //свой маппер тут.
            return new \GateAuth\Mapper\User(

            );
        },
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

}
