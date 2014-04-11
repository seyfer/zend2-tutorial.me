<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Users\Form\RegisterForm;
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

}
