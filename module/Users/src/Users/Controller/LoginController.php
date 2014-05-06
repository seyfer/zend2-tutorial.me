<?php

namespace Users\Controller;

use Users\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Users\Form\LoginForm;
use Users\Model\AuthStorage;

/**
 * Description of LoginController
 *
 * @author seyfer
 */
class LoginController extends BaseController
{

    /**
     *
     * @var AuthStorage
     */
    protected $storage;

    public function indexAction()
    {
        $form      = new LoginForm();
        $viewModel = new ViewModel(array('form' => $form));
        return $viewModel;
    }

    /**
     *
     * @return AuthStorage
     */
    public function getSessionStorage()
    {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()
                    ->get('AuthStorageUsers');
        }

        return $this->storage;
    }

    public function processAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(NULL, array(
                        'controller' => 'login',
                        'action'     => 'index'
            ));
        }

        $post = $this->request->getPost();
        $form = $this->getServiceLocator()->get('LoginForm');

        $form->setData($post);
        if ($form->isValid()) {

            $this->getAuthService()->getAdapter()->setIdentity(
                    $this->request->getPost('email'))->setCredential(
                    $this->request->getPost('password'));

            $result = $this->getAuthService()->authenticate();

            if ($result->isValid()) {
                if ($this->request->getPost('rememberme') == 1) {
                    $this->getSessionStorage()
                            ->setRememberMe(1);
                    //set storage again
                    $this->getAuthService()->setStorage($this->getSessionStorage());
                }

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

}
