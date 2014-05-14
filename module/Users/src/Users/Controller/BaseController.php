<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;

/**
 * Description of BaseController
 *
 * @author seyfer
 */
class BaseController extends AbstractActionController
{

    /**
     *
     * @var AuthenticationService
     */
    protected $authservice;

    /**
     * Определение класса
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthServiceUsers');
        }
        return $this->authservice;
    }

    protected function getFileUploadLocation()
    {
        // Получение конфигурации из конфигурационных данных модуля
        $config = $this->getServiceLocator()->get('config');

        return $config['module_config']['upload_location'];
    }

}
