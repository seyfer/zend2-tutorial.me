<?php

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\ViewModel;
use Auth\Model\User;

/**
 * Description of AuthController
 *
 * @author seyfer
 */
class AuthController extends AbstractActionController {

    protected $form;
    protected $storage;
    protected $authservice;

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

    public function getForm()
    {
        if (!$this->form) {
            $user       = new User();
            $builder    = new AnnotationBuilder();
            $this->form = $builder->createForm($user);
        }

        return $this->form;
    }

    private function redirectToSuccess()
    {
        \Application\Debug::dump(__METHOD__);

        $adapter = $this->getAuthService()->getAdapter();
        $adapter->clearAvailableContracts();

        $this->redirect()->toUrl('/success');
    }

    public function loginAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()) {
            $this->redirectToSuccess();
        }

        $form = $this->getForm();

        if ($this->params()->fromQuery("warning")) {
            $adapter = $this->getAuthService()->getAdapter();

            $contracts = $adapter->getAvailableContracts();

//            \Application\Debug::dump($_SESSION);
        }

        return array(
            'form'      => $form,
            'contracts' => $contracts,
            'messages'  => $this->flashmessenger()->getMessages()
        );
    }

    public function authenticateAction()
    {
        $form     = $this->getForm();
        $redirect = 'login';

        \Application\Debug::dump($this->getRequest()->getPost());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {

                try {

                    //check authentication...
                    $this->getAuthService()->getAdapter()
                            ->setIdentity($request->getPost('username'))
                            ->setCredential($request->getPost('password'))
                            ->setContract($request->getPost('contracts'));

                    $result = $this->getAuthService()->authenticate();

                    foreach ($result->getMessages() as $message) {
                        //save message temporary into flashmessenger
                        $this->flashmessenger()->addMessage($message);
                    }

                    if ($result->isValid()) {
                        //check if it has rememberMe :
                        if ($request->getPost('rememberme') == 1) {

                            $this->getSessionStorage()
                                    ->setRememberMe(1);

                            //set storage again
                            $this->getAuthService()
                                    ->setStorage($this->getSessionStorage());
                        }

                        $this->getAuthService()
                                ->getStorage()->write($request->getPost('username'));

                        $this->redirectToSuccess();
                    }
                    else {
                        $adapter = $this->getAuthService()->getAdapter();
                        $code    = $adapter->getStatus();

                        if ($code == \Auth\Model\GateAdapter::STATUS_WARNING) {
                            return $this->redirect()->toRoute($redirect, array(), array(
                                        "query" => array(
                                            "warning" => "1",
                                        )
                            ));
                        }
                    }
                }
                catch (\Exception $e) {
                    \Application\Debug::dump($e->getMessage());
                }
            }
        }

        $this->redirect()->toRoute($redirect);
    }

    public function logoutAction()
    {
        $this->
                getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();

        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }

}
