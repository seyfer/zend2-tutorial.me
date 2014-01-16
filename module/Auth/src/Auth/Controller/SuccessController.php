<?php

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Description of SuccessController
 *
 * @author seyfer
 */
class SuccessController extends AbstractActionController {

    public function indexAction()
    {
//        \Zend\Debug\Debug::dump($_SESSION);

        if (!$this->getServiceLocator()
                        ->get('AuthService')->hasIdentity()) {

            return $this->redirect()->toRoute('login');
        }

        return new ViewModel();
    }

}
