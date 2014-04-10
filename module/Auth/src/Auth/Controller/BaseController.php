<?php

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Description of BaseController
 *
 * @author seyfer
 */
class BaseController extends AbstractActionController
{

    protected $storage;
    protected $authservice;

    /**
     *
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthService()
    {
//        \Application\Debug::dump(__METHOD__);

        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()
                    ->get('AuthService');
        }

//        \Zend\Debug\Debug::dump(get_class($this->authservice->getAdapter()));

        return $this->authservice;
    }

    public function getSessionStorage()
    {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()
                    ->get('Auth\Model\AuthStorage');
        }

        return $this->storage;
    }

}
