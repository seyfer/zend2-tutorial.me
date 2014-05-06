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

    const GOOGLE_USER_ID  = 'seyferseed@mail.ru';
    const GOOGLE_PASSWORD = '';

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

        if (self::GOOGLE_PASSWORD) {
            $googleAlbums = $this->getGooglePhotos();
            $googleVideos = $this->getGoogleVideos();
        }

        $viewModel = new ViewModel(array(
            'myUploads'    => $myUploads,
            'uploadPath'   => $this->getFileUploadLocation(),
            'googleAlbums' => $googleAlbums,
            'googleVideos' => $googleVideos,
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

    /**
     *
     * @return Zend\Cache\Storage\Adapter\Apc
     */
    private function getApcCache()
    {
        return \Zend\Cache\StorageFactory::factory(array(
                    'adapter' => array(
                        'name'    => 'apc',
                        // With a namespace we can indicate the same type of items
                        // -> So we can simple use the db id as cache key
                        'options' => array(
                            'namespace' => 'google'
                        ),
                    ),
                    'plugins' => array(
                        // Don't throw exceptions on cache errors
                        'exception_handler' => array(
                            'throw_exceptions' => false
                        ),
                        // We store database rows on filesystem so we need to serialize them
                        'Serializer'
                    )
        ));
    }

    /**
     * @deprecated since version number
     * not maintained
     * @return type
     */
    private function getGoogleVideos()
    {
        $yVideo = [];
        $cache  = $this->getApcCache();
        $key    = 'google_yVideo';
        if (!$cache->getItem($key)) {
            $client = $this->getGoogleClient();

//        \Zend\Debug\Debug::dump($client);

            $yt    = new \ZendGData\YouTube($client);
            $yt->setMajorProtocolVersion(2);
            $query = $yt->newVideoQuery();
            $query->setOrderBy('relevance');
            $query->setSafeSearch('none');
            $query->setVideoQuery('Zend Framework');

//        \Zend\Debug\Debug::dump($userFeed);

            $videoFeed = $yt->getVideoFeed($query->getQueryUrl(2));
            $yVideos   = array();
            foreach ($videoFeed as $videoEntry) {
                $yVideo                     = array();
                $yVideo['videoTitle']       = $videoEntry->getVideoTitle();
                $yVideo['videoDescription'] = $videoEntry->getVideoDescription();
                $yVideo['watchPage']        = $videoEntry->getVideoWatchPageUrl();
                $yVideo['duration']         = $videoEntry->getVideoDuration();
                $videoThumbnails            = $videoEntry->getVideoThumbnails();
                $yVideo['thumbnailUrl']     = $videoThumbnails[0]['url'];
                $yVideos[]                  = $yVideo;
            }

            $cache->setItem($key, $yVideo);
        } else {
            $yVideo = $cache->getItem($key);
        }

        // Возвращение объединенного массива в представление для визуализации
        return $yVideo;
    }

    /**
     *
     * @return \ZendGData\HttpClient
     */
    private function getGoogleClient()
    {
        $adapter    = new \Zend\Http\Client\Adapter\Curl();
        $adapter->setOptions(array(
            'curloptions' => array(
                CURLOPT_SSL_VERIFYPEER => false,
            )
        ));
        $httpClient = new \ZendGData\HttpClient();
        $httpClient->setAdapter($adapter);
        $client     = \ZendGData\ClientLogin::getHttpClient(
                        self::GOOGLE_USER_ID, self::GOOGLE_PASSWORD, \ZendGData\Photos::AUTH_SERVICE_NAME, $httpClient);

        return $client;
    }

    private function getGooglePhotos()
    {
        $gAlbums = [];
        $cache   = $this->getApcCache();
        $key     = 'google_photos';
        if (!$cache->getItem($key)) {
            $client = $this->getGoogleClient();

//        \Zend\Debug\Debug::dump($client);

            $gp       = new \ZendGData\Photos($client);
            $userFeed = $gp->getUserFeed(self::GOOGLE_USER_ID);

//        \Zend\Debug\Debug::dump($userFeed);

            foreach ($userFeed as $userEntry) {
                $albumId                    = $userEntry->getGphotoId()->getText();
                $gAlbums[$albumId]['label'] = $userEntry->getTitle()->getText();

                $query     = $gp->newAlbumQuery();
                $query->setUser(self::GOOGLE_USER_ID);
                $query->setAlbumId($albumId);
                $albumFeed = $gp->getAlbumFeed($query);

//            \Zend\Debug\Debug::dump($albumFeed);

                foreach ($albumFeed as $photoEntry) {
                    $photoId = $photoEntry->getGphotoId()->getText();

                    if ($photoEntry->getMediaGroup()->getContent() != null) {
                        $mediaContentArray = $photoEntry->getMediaGroup()->getContent();
                        $photoUrl          = $mediaContentArray[0]->getUrl();
                    }

                    if ($photoEntry->getMediaGroup()->getThumbnail() != null) {
                        $mediaThumbnailArray = $photoEntry->getMediaGroup()
                                ->getThumbnail();
                        $thumbUrl            = $mediaThumbnailArray[0]->getUrl();
                    }

                    $albumPhoto             = array();
                    $albumPhoto['id']       = $photoId;
                    $albumPhoto['photoUrl'] = $photoUrl;
                    $albumPhoto['thumbUrl'] = $thumbUrl;
                }

                $gAlbums[$albumId]['photos'][] = $albumPhoto;
            }

            $cache->setItem($key, $gAlbums);
        } else {
            $gAlbums = $cache->getItem($key);
        }

        // Возвращение объединенного массива в представление для визуализации
        return $gAlbums;
    }

}
