<?php

namespace Users\Controller;

use Users\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Users\Form\UploadForm;

/**
 * Description of MediaManagerController
 *
 * @author seyfer
 */
class MediaManagerController extends BaseController
{

    public function indexAction()
    {
        // Получение информации о пользователе от сеанса
        $userEmail = $this->getAuthService()->getStorage()->read();
        if (!$userEmail) {
            $this->flashMessenger()->addErrorMessage("not authorized");
            return array();
        }

        $uploadTable = $this->getServiceLocator()->get('ImageUploadTable');
        $userTable   = $this->getServiceLocator()->get('UserTable');

        $user      = $userTable->getUserByEmail($userEmail);
        $myUploads = $uploadTable->getUploadsByUserId($user->getId());

        $viewModel = new ViewModel(array(
            'myUploads'  => $myUploads,
            'uploadPath' => $this->getFileUploadLocation()
        ));

        return $viewModel;
    }

    public function uploadAction()
    {
        $form = new UploadForm();

        return array(
            'form' => $form,
        );
    }

    public function generateThumbnail($imageFileName)
    {
        $path                = $this->getFileUploadLocation();
        $sourceImageFileName = $path . DIRECTORY_SEPARATOR . $imageFileName;
        $thumbnailFileName   = 'tn_' . $imageFileName;

        $imageThumb = $this->getServiceLocator()->get('WebinoImageThumb');
        $thumb      = $imageThumb
                ->create($sourceImageFileName, $options    = array());
        $thumb->resize(75, 75);
        $thumb->save($path . DIRECTORY_SEPARATOR . $thumbnailFileName);

        return $thumbnailFileName;
    }

    /**
     * достать картинку по данным из базы
     * взять контент с диска и отдать
     * @return type
     */
    public function showImageAction()
    {
        $uploadId    = $this->params()->fromRoute('id');
        $uploadTable = $this->getServiceLocator()->get('ImageUploadTable');
        $upload      = $uploadTable->getById($uploadId);

        // Выборка конфигурации из модуля
        $uploadPath = $this->getFileUploadLocation();
        $subAction  = $this->params()->fromRoute('subaction');
        if ($subAction == 'thumb') {
            $imageName = $upload->getThumbnail();
            $filename  = $uploadPath . "/" . $upload->getThumbnail();
        } else {
            $imageName = $upload->getFilename();
            $filename  = $uploadPath . "/" . $upload->getFilename();
        }
        $file = file_get_contents($filename);

        // Прямой возврат ответа
        $response = $this->getEvent()->getResponse();
        $response->getHeaders()->addHeaders(array(
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => 'attachment;filename="'
            . $imageName . '"',
        ));

        $response->setContent($file);

        return $response;
    }

    public function viewAction()
    {
        $uploadId    = $this->params()->fromRoute('id');
        $uploadTable = $this->getServiceLocator()->get('ImageUploadTable');
        $upload      = $uploadTable->getById($uploadId);

        return array('upload' => $upload);
    }

    public function rotateAction()
    {
        $uploadId    = $this->params()->fromRoute('id');
        $uploadTable = $this->getServiceLocator()->get('ImageUploadTable');
        $upload      = $uploadTable->getById($uploadId);

        // Выборка конфигурации из модуля
        $uploadPath = $this->getFileUploadLocation();
        $subAction  = $this->params()->fromRoute('subaction');

        $thumbName = $uploadPath . DIRECTORY_SEPARATOR . $upload->getThumbnail();
        $fileName  = $uploadPath . DIRECTORY_SEPARATOR . $upload->getFilename();

        $imageThumb = $this->getServiceLocator()->get('WebinoImageThumb');
        if ($subAction == 'left') {

            $thumb   = $imageThumb
                    ->create($thumbName, $options = array());
            $thumb->rotateImageNDegrees(-90);
            $thumb->save($thumbName);
            $file    = $imageThumb
                    ->create($fileName, $options = array());
            $file->rotateImageNDegrees(-90);
            $file->save($fileName);
        } else if ($subAction == 'right') {
            $thumb   = $imageThumb
                    ->create($thumbName, $options = array());
            $thumb->rotateImageNDegrees(90);
            $thumb->save($thumbName);
            $file    = $imageThumb
                    ->create($fileName, $options = array());
            $file->rotateImageNDegrees(90);
            $file->save($fileName);
        }

        return $this->redirect()->toRoute('media', array(
                    'action' => 'view',
                    'id'     => $uploadId,
        ));
    }

    private function getFileUploadLocation()
    {
        // Получение конфигурации из конфигурационных данных модуля
        $config = $this->getServiceLocator()->get('config');

        return $config['module_config']['upload_location'];
    }

    public function processAction()
    {
        $userEmail = $this->getAuthService()->getStorage()->read();
        if (!$userEmail) {
            $this->flashMessenger()->addErrorMessage("not authorized");
            return $this->redirect()->
                            toRoute('media', array('action' => 'index'));
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

//                // Успешная выгрузка файла
                $exchange_data             = array();
                $exchange_data['label']    = $request->getPost()->get('label');
                $exchange_data['filename'] = $uploadFile['name'];
                $exchange_data['user_id']  = $user->getId();

                $imageUpload                 = new \Users\Model\ImageUpload();
                $thumbnailFileName           = $this->generateThumbnail($uploadFile['name']);
                $thumbnail_data              = $exchange_data;
                $thumbnail_data['thumbnail'] = $thumbnailFileName;
                $imageUpload->exchangeArray($thumbnail_data);

                $imageUploadTable = $this->getServiceLocator()->get('ImageUploadTable');
                $imageUploadTable->save($imageUpload);
            }
        }

        return $this->redirect()->
                        toRoute('media', array('action' => 'index'));
    }

}
