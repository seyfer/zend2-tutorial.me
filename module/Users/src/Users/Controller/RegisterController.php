<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Users\Form\RegisterForm,
    Users\Form\Filter\RegisterFilter;
use Zend\View\Model\ViewModel;

/**
 * Description of RegisterController
 *
 * @author seyfer
 */
class RegisterController extends AbstractActionController
{

    public function indexAction()
    {
        $form      = new RegisterForm();
        $viewModel = new ViewModel(array('form' => $form));
        return $viewModel;
    }

    public function confirmAction()
    {
        $viewModel = new ViewModel();
        return $viewModel;
    }

    public function processAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(NULL, array('controller' => 'register',
                        'action'     => 'index'
            ));
        }

        $post        = $this->request->getPost();
        $form        = new RegisterForm();
        $inputFilter = new RegisterFilter();
        $form->setInputFilter($inputFilter);

        $form->setData($post);
        if (!$form->isValid()) {
            $model = new ViewModel(array(
                'error' => true,
                'form'  => $form,
            ));
            $model->setTemplate('users/register/index');
            return $model;
        }

        return $this->redirect()->toRoute(NULL, array(
                    'controller' => 'register',
                    'action'     => 'confirm'
        ));
    }

}
