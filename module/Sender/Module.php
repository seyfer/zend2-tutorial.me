<?php

namespace Sender;

/**
 * Description of Module
 *
 * @author seyfer
 */
class Module {

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

//    public function getServiceConfig()
//    {
//        return array(
//            "factories" => array(
//            )
//        );
//    }

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

}
