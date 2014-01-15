<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController {

    public function indexAction()
    {

    }

    public function loginAction()
    {
        $result = $this->auth->authenticate($adapter);

        switch ($result->getCode()) {

            case Result::FAILURE_IDENTITY_NOT_FOUND:
                /** do stuff for nonexistent identity * */
                break;

            case Result::FAILURE_CREDENTIAL_INVALID:
                /** do stuff for invalid credential * */
                break;

            case Result::SUCCESS:
                /** do stuff for successful authentication * */
                break;

            default:
                /** do stuff for other failure * */
                break;
        }
    }

    public function logoutAction()
    {

    }

}
