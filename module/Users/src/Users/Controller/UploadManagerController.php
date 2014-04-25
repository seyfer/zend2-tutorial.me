<?php

namespace Users\Controller;

use Users\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Users\Form\UploadForm;

/**
 * Description of UploadManagerController
 *
 * @author seyfer
 */
class UploadManagerController extends BaseController
{

    public function indexAction()
    {
        // Получение информации о пользователе от сеанса
        $userEmail = $this->getAuthService()->getStorage()->read();
        if (!$userEmail) {
            $this->flashMessenger()->addErrorMessage("not authorized");
            return array();
        }

        $uploadTable = $this->getServiceLocator()->get('UploadTable');
        $userTable   = $this->getServiceLocator()->get('UserTable');

        $user      = $userTable->getUserByEmail($userEmail);
        $viewModel = new ViewModel(array(
            'myUploads'  => $uploadTable->getUploadsByUserId($user->getId()),
            'uploadPath' => $this->getFileUploadLocation()
        ));

        return $viewModel;
    }

    public function processAction()
    {
        $userEmail = $this->getAuthService()->getStorage()->read();
        if (!$userEmail) {
            $this->flashMessenger()->addErrorMessage("not authorized");
            return array();
        }

        $request = $this->getRequest();
        $form    = new UploadForm();

        $uploadFile = $this->params()->fromFiles('fileupload');
        $form->setData($request->getPost());

        if ($form->isValid()) {

            // Получение конфигурации из конфигурационных данных модуля
            $uploadPath = $this->getFileUploadLocation();

            // Сохранение выгруженного файла
            $adapter = new \Zend\File\Transfer\Adapter\Http();
            $adapter->setDestination($uploadPath);
            if ($adapter->receive($uploadFile['name'])) {

                $userTable = $this->getServiceLocator()->get('UserTable');
                $user      = $userTable->getUserByEmail($userEmail);

                $upload = new \Users\Model\Upload();

                // Успешная выгрузка файла
                $exchange_data             = array();
                $exchange_data['label']    = $request->getPost()->get('label');
                $exchange_data['filename'] = $uploadFile['name'];
                $exchange_data['user_id']  = $user->getId();
                $upload->exchangeArray($exchange_data);

                $uploadTable = $this->getServiceLocator()->get('UploadTable');
                $uploadTable->save($upload);
            }
        }

        return $this->redirect()->
                        toRoute('uploads', array('action' => 'index'));
    }

    public function uploadAction()
    {
        $form = new UploadForm();

        return array(
            'form' => $form,
        );
    }

    private function getFileUploadLocation()
    {
        // Получение конфигурации из конфигурационных данных модуля
        $config = $this->getServiceLocator()->get('config');

//        echo $config['module_config']['upload_location'];
//        exit();

        return $config['module_config']['upload_location'];
    }

    /**
     * Загрузка файла
     * @return \Zend\Http\Response\Stream
     */
    public function downloadAction()
    {
        if ($this->request->isGet()) {

            $fileId = $this->params("id");

            $uploadTable = $this->getServiceLocator()->get('UploadTable');
            $upload      = $uploadTable->getById($fileId);

            $fileName        = $upload->getFileName();
            $fileInitialName = $upload->getFileName();

            $fileNameP = $this->getFileUploadLocation() . DIRECTORY_SEPARATOR .
                    $fileName;

            $response = new \Zend\Http\Response\Stream();
            $response->setStream(fopen($fileNameP, 'r'));
            $response->setStatusCode(200);

            $finfo   = finfo_open(FILEINFO_MIME_TYPE);
            $headers = new \Zend\Http\Headers();
            $headers->addHeaderLine('Content-Type', finfo_file($finfo, $fileNameP))
                    ->addHeaderLine('Content-Disposition', 'attachment; filename="' .
                            $fileInitialName . '"')
                    ->addHeaderLine('Content-Length', filesize($fileNameP));

            $response->setHeaders($headers);
            return $response;
        }
    }

}
