<?php

namespace Application\Controller;

use Application\Model\MyAdapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController {

    public function indexAction()
    {

    }

    public function loginAction()
    {
        $login    = "seyfer";
        $password = "seed1212";

        $adapter = new MyAdapter($login, $password);

        $result = $this->auth->authenticate($adapter);

        $code     = $result->getCode();
        $identity = $result->getIdentity();

//        switch ($result->getCode()) {
//
//            case Result::FAILURE_IDENTITY_NOT_FOUND:
//                /** do stuff for nonexistent identity * */
//                break;
//
//            case Result::FAILURE_CREDENTIAL_INVALID:
//                /** do stuff for invalid credential * */
//                break;
//
//            case Result::SUCCESS:
//                /** do stuff for successful authentication * */
//                break;
//
//            default:
//                /** do stuff for other failure * */
//                break;
//        }

        return array(
            "code"     => $code,
            "identity" => $identity,
        );
    }

    public function logoutAction()
    {

    }

}
