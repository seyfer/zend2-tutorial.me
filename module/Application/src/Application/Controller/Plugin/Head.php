<?php

namespace Application\Controller\Plugin;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Mvc\Controller\Plugin\PluginInterface,
    Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Description of Head
 *
 * @todo implement..
 *
 * @author seyfer
 */
class Head extends AbstractPlugin implements
ServiceLocatorAwareInterface, PluginInterface
{

    public function __construct()
    {
//        return $this->getServiceLocator()
//                        ->getServiceLocator() // Main service Locator
//                        ->get('viewhelpermanager')
//                        ->get('HeadScript');
    }

    public function getServiceLocator()
    {
        return $this;
    }

    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        return;
    }

    public function getController()
    {
        return;
    }

    public function setController(\Zend\Stdlib\DispatchableInterface $controller)
    {
        return;
    }

}
