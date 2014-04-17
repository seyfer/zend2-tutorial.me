<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Users\Form\LoginForm;

/**
 * Description of LoginController
 *
 * @author seyfer
 */
class LoginController extends AbstractActionController
{

    public function indexAction()
    {
        $form      = new LoginForm();
        $viewModel = new ViewModel(array('form' => $form));
        return $viewModel;
    }

    public function processAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(NULL, array(
                        'controller' => 'login',
                        'action'     => 'index'
            ));
        }

        $post        = $this->request->getPost();
        $form        = new \Users\Form\LoginForm();
        $inputFilter = new \Users\Form\Filter\LoginFilter();
        $form->setInputFilter($inputFilter);

        $form->setData($post);
        if ($form->isValid()) {

            $this->getAuthService()->getAdapter()->setIdentity(
                    $this->request->getPost('email'))->setCredential(
                    $this->request->getPost('password'));

            $result = $this->getAuthService()->authenticate();

            if ($result->isValid()) {
                $this->getAuthService()->getStorage()->write(
                        $this->request->getPost('email'));

                return $this->redirect()->toRoute(NULL, array(
                            'controller' => 'login',
                            'action'     => 'confirm'
                ));
            }
        }

        $model = new ViewModel(array(
            'error' => true,
            'form'  => $form,
        ));
        $model->setTemplate('users/login/index');
        return $model;
    }

    public function confirmAction()
    {
        $user_email = $this->getAuthService()->getStorage()->read();
        $viewModel  = new ViewModel(array(
            'user_email' => $user_email
        ));
        return $viewModel;
    }

    // Определение класса
    public function getAuthService()
    {
        if (!$this->authservice) {
            $dbAdapter          = $this->getServiceLocator()->get(
                    'Zend\Db\Adapter\Adapter');
            $dbTableAuthAdapter = new DbTableAuthAdapter(
                    $dbAdapter, 'myuser', 'email', 'password', 'MD5(?)');
            $authService        = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            $this->authservice  = $authService;
        }
        return $this->authservice;
    }

}
