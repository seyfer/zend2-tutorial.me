<?php

namespace Users\Controller;

use Users\Controller\BaseController;
use Zend\View\Model\ViewModel;

/**
 * Description of UploadManagerController
 *
 * @author seyfer
 */
class UploadManagerController extends BaseController
{

    public function indexAction()
    {
        $uploadTable = $this->getServiceLocator()->get('UploadTable');
        $userTable   = $this->getServiceLocator()->get('UserTable');
        // Получение информации о пользователе от сеанса
        $userEmail   = $this->getAuthService()->getStorage()->read();
        $user        = $userTable->getUserByEmail($userEmail);
        $viewModel   = new ViewModel(array(
            'myUploads' => $uploadTable->getUploadsByUserId($user->getId()),
        ));
        return $viewModel;
    }

    public function addAction()
    {
        
    }

}
