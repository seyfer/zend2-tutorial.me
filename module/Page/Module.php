<?php

namespace Page;

use Page\Model\PageTable;

class Module {

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            "factories" => array(
                "Page\Model\PageTable" => function($servMan) {
            $dbAdapter = $servMan->get("Zend\Db\Adapter\Adapter");
            $dbTable   = new PageTable($dbAdapter);
            return $dbTable;
        }
            )
        );
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

}
