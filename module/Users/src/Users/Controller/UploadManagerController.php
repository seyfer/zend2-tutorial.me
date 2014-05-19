<?php

namespace Users\Controller;

use Users\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Users\Form\UploadForm;
use ZendSearch\Lucene;
use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Index;

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

        $user          = $userTable->getUserByEmail($userEmail);
        $sharedUploads = $uploadTable->getSharedUploadsForUserId($user->getId());
        $myUploads     = $uploadTable->getUploadsByUserId($user->getId());

        $sharedUploads->buffer();
        foreach ($sharedUploads as $sharedUpload) {
            $userOwner                        = $userTable->getById($sharedUpload['user_id']);
            $usersOwners[$sharedUpload['id']] = $userOwner->getEmail();
        }

        $viewModel = new ViewModel(array(
            'myUploads'     => $myUploads,
            'sharedUploads' => $sharedUploads,
            'usersOwners'   => $usersOwners,
            'uploadPath'    => $this->getFileUploadLocation()
        ));

        return $viewModel;
    }

    public function processjqueryAction()
    {
        $request   = $this->getRequest();
        $response  = $this->getResponse();
        $jsonModel = new \Zend\View\Model\JsonModel();

        if ($request->isPost()) {

            try {
                $datas          = [];
                $datas['files'] = [];
                $uploadPath     = $this->getFileUploadLocation();
//                $uploadFiles    = $this->params()->fromFiles('files');

//                throw new \Exception(json_encode("FILES " . serialize($_FILES)));
                // Сохранение выгруженного файла
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setDestination($uploadPath);
                $adapter->setValidators(array(
                    new \Zend\Validator\File\Extension(array(
                        'extension' => array('jpg', 'jpeg', 'png', 'rtf')
                            )
                    ),
//                    new \Zend\Validator\File\Upload()
                ));
                $adapter->setFilters(array(
                    new \Zend\Filter\File\RenameUpload(array(
                        'target'    => $uploadPath . '../tmpuploads/tmp',
                        'randomize' => true,
                            )
                    )
                ));

                if (!$adapter->isValid()) {
                    throw new \Exception(json_encode("!isValid " . implode(" ", $adapter->getMessages())));
                }

                $files = $adapter->getFileInfo();
//                throw new \Exception(json_encode($files));

                foreach ($files as $file => $info) {
//                    throw new \Exception(json_encode($info));

                    $name = $adapter->getFileName($file);

                    // file uploaded & is valid
                    if (!$adapter->isUploaded($file)) {
                        throw new \Exception(json_encode("!isUploaded") . implode(" ", $adapter->getMessages()));
                        continue;
                    }

                    if (!$adapter->isValid($file)) {
                        throw new \Exception(json_encode("!isValid " . implode(" ", $adapter->getMessages())));
                        continue;
                    }

                    // receive the files into the user directory
                    $check = $adapter->receive($file); // this has to be on top

                    if (!$check) {
                        throw new \Exception(json_encode("! receive" . implode(" ", $adapter->getMessages())));
                    }

                    /**
                     * "name": "picture1.jpg",
                      "size": 902604,
                      "url": "http:\/\/example.org\/files\/picture1.jpg",
                      "thumbnailUrl": "http:\/\/example.org\/files\/thumbnail\/picture1.jpg",
                      "deleteUrl": "http:\/\/example.org\/files\/picture1.jpg",
                      "deleteType": "DELETE"
                     */
                    $fileclass             = new stdClass();
                    // we stripped out the image thumbnail for our purpose, primarily for security reasons
                    // you could add it back in here.
                    $fileclass->name       = $name;
                    $fileclass->size       = $adapter->getFileSize($name);
                    $fileclass->type       = $adapter->getMimeType($name);
                    $fileclass->deleteUrl  = '/uploads/delete';
                    $fileclass->deleteType = 'DELETE';
                    //$fileclass->error = 'null';
                    $fileclass->url        = '/';

                    $datas['files'][] = $fileclass;
                }

                $response->getHeaders()->addHeaders(array(
                    'Pragma'        => 'no-cache',
                    'Cache-Control' => 'private, no-cache',
                    "Content-Type"  => 'application/json'
                ));

//                return $response->setContent(json_encode(array('files' => $files)));
                return $response->setContent(json_encode($datas));
            } catch (\Exception $e) {

                return $response->setContent(json_encode($e->getMessage()));
            }
        }

        return $jsonModel;

//        if ($request->isPost()) {
//                // Получение конфигурации из конфигурационных данных модуля
//                $uploadPath = $this->getFileUploadLocation();
//
//                // Сохранение выгруженного файла
//                $adapter = new \Zend\File\Transfer\Adapter\Http();
//                $adapter->setDestination($uploadPath);
//                if ($adapter->receive($uploadFile['name'])) {
//
//                    $userTable = $this->getServiceLocator()->get('UserTable');
//                    $user      = $userTable->getUserByEmail($userEmail);
//
//                    $upload = new \Users\Model\Upload();
//
//                    // Успешная выгрузка файла
//                    $exchange_data             = array();
//                    $exchange_data['label']    = $request->getPost()->get('label');
//                    $exchange_data['filename'] = $uploadFile['name'];
//                    $exchange_data['user_id']  = $user->getId();
//                    $upload->exchangeArray($exchange_data);
//
//                    $uploadTable = $this->getServiceLocator()->get('UploadTable');
//                    $uploadTable->save($upload);
//                    $upload->setId($uploadTable->getLastInsertValue());
//
//                    //добавить в Lucene
//                    $searchIndexLocation = $this->getIndexLocation();
//                    $index               = Lucene\Lucene::create($searchIndexLocation);
//
//                    // создание полей lucene
//                    $fileUploadId = Document\Field::unIndexed(
//                                    'upload_id', $upload->getId());
//                    $label        = Document\Field::Text('label', $upload->getLabel());
//                    $owner        = Document\Field::Text('owner', $user->getName());
//
//                    $uploadPath = $this->getFileUploadLocation();
//                    $fileName   = $upload->getFilename();
//                    $filePath   = $uploadPath . DIRECTORY_SEPARATOR . $fileName;
//
//                    if (substr_compare($fileName, ".xlsx", strlen($fileName) -
//                                    strlen(".xlsx"), strlen(".xlsx")) === 0) {
//                        // Индексирование таблицы excel
//                        $indexDoc = Lucene\Document\Xlsx::loadXlsxFile($filePath);
//                    } else if (substr_compare($fileName, ".docx", strlen($fileName) -
//                                    strlen(".docx"), strlen(".docx")) === 0) {
//                        // Индексирование документа Word
//                        $indexDoc = Lucene\Document\Docx::loadDocxFile($filePath);
//                    } else {
//                        $indexDoc = new Lucene\Document();
//                    }
//
//                    // создание нового документа и добавление всех полей
//                    $indexDoc = new Lucene\Document();
//                    $indexDoc->addField($label);
//                    $indexDoc->addField($owner);
//                    $indexDoc->addField($fileUploadId);
//                    $index->addDocument($indexDoc);
//
//                    $index->commit();
//                }
//        }
//        return $response->setContent("");
    }

    public function processAction()
    {
        $userEmail = $this->getAuthService()->getStorage()->read();
        if (!$userEmail) {
            $this->flashMessenger()->addErrorMessage("not authorized");
            return $this->getResponse()->setContent("not authorized");
        }

        $request = $this->getRequest();
        $form    = new UploadForm();

        $uploadFile = $this->params()->fromFiles('fileupload');

        if ($request->isPost()) {
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
                    $upload->setId($uploadTable->getLastInsertValue());

                    //добавить в Lucene
                    $searchIndexLocation = $this->getIndexLocation();
                    $index               = Lucene\Lucene::create($searchIndexLocation);

                    // создание полей lucene
                    $fileUploadId = Document\Field::unIndexed(
                                    'upload_id', $upload->getId());
                    $label        = Document\Field::Text('label', $upload->getLabel());
                    $owner        = Document\Field::Text('owner', $user->getName());

                    $uploadPath = $this->getFileUploadLocation();
                    $fileName   = $upload->getFilename();
                    $filePath   = $uploadPath . DIRECTORY_SEPARATOR . $fileName;

                    if (substr_compare($fileName, ".xlsx", strlen($fileName) -
                                    strlen(".xlsx"), strlen(".xlsx")) === 0) {
                        // Индексирование таблицы excel
                        $indexDoc = Lucene\Document\Xlsx::loadXlsxFile($filePath);
                    } else if (substr_compare($fileName, ".docx", strlen($fileName) -
                                    strlen(".docx"), strlen(".docx")) === 0) {
                        // Индексирование документа Word
                        $indexDoc = Lucene\Document\Docx::loadDocxFile($filePath);
                    } else {
                        $indexDoc = new Lucene\Document();
                    }

                    // создание нового документа и добавление всех полей
                    $indexDoc = new Lucene\Document();
                    $indexDoc->addField($label);
                    $indexDoc->addField($owner);
                    $indexDoc->addField($fileUploadId);
                    $index->addDocument($indexDoc);

                    $index->commit();
                }
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

    public function uploadJqueryAction()
    {
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setTemplate("users/upload-manager/uploadjquery1");

        return $view;
    }

    public function editAction()
    {
        $uploadId = $this->params('id');

        $form = $this->getServiceLocator()->get('UploadForm');
        $form->initSharedUsersSelect($uploadId);

        $uploadTable = $this->getServiceLocator()->get('UploadTable');
        $upload      = $uploadTable->getById($uploadId);

        $form->setData($upload->getArrayCopy());

        if ($this->request->isPost()) {
            $post = $this->request->getPost()->toArray();

            foreach ($post['shared_user_ids'] as $idToShare) {
                $uploadTable->addSharing($uploadId, $idToShare);
            }
        }

        return array(
            'form'     => $form,
            'uploadId' => $uploadId,
        );
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

    public function deleteAction()
    {
        $fileId = $this->params("id");

        $uploadTable = $this->getServiceLocator()->get('UploadTable');
        $upload      = $uploadTable->getById($fileId);

        $fileName = $upload->getFileName();

        $fileNameP = $this->getFileUploadLocation() . DIRECTORY_SEPARATOR .
                $fileName;

        if (file_exists($fileNameP)) {
            unlink($fileNameP);
        }

        //удалить из Lucene
        $searchIndexLocation = $this->getIndexLocation();
        $index               = Lucene\Lucene::create($searchIndexLocation);
        $document            = $index->find($fileId);

        if ($document) {
            $index->delete($document->document_id);
            $index->commit();
        }

        $uploadTable->deleteById($fileId);

        return $this->redirect()->
                        toRoute('uploads', array('action' => 'index'));
    }

}
