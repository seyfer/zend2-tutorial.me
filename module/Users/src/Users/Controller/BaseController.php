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

        $path = $config['module_config']['upload_location'];

        if (!is_writable($path)) {
            throw new \Exception(__METHOD__ . $path . " not writable");
        }

        return $path;
    }

    public function getIndexLocation()
    {
        // выборка конфигурации из конфигурационных данных модуля
        $config = $this->getServiceLocator()->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        if (!empty($config['module_config']['search_index'])) {
            return $config['module_config']['search_index'];
        } else {
            return FALSE;
        }
    }

}
