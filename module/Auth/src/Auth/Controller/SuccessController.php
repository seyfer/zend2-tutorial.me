<?php

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Description of SuccessController
 *
 * @author seyfer
 */
class SuccessController extends BaseController {

    public function indexAction()
    {
//        \Application\Debug::dump($_SESSION);
//        \Application\Debug::dump($_COOKIE);

        if (!$this->getAuthService()->hasIdentity()) {

            return $this->redirect()->toRoute('login');
        }

        return new ViewModel();
    }

}
