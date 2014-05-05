<?php

namespace Users\Controller;

use Users\Controller\BaseController;
use Zend\View\Model\ViewModel;

/**
 * Description of MediaManagerController
 *
 * @author seyfer
 */
class MediaManagerController extends BaseController
{

    public function indexAction()
    {
        
    }

    public function generateThumbnail($imageFileName)
    {
        $path                = $this->getFileUploadLocation();
        $sourceImageFileName = $path . DIRECTORY_SEPARATOR . $imageFileName;

        $thumbnailFileName = 'tn_' . $imageFileName;
        $imageThumb        = $this->getServiceLocator()->get('WebinoImageThumb');
        $thumb             = $imageThumb
                ->create($sourceImageFileName, $options           = array());
        $thumb->resize(75, 75);
        $thumb->save($path . DIRECTORY_SEPARATOR . $thumbnailFileName);

        return $thumbnailFileName;
    }

    private function getFileUploadLocation()
    {
        // Получение конфигурации из конфигурационных данных модуля
        $config = $this->getServiceLocator()->get('config');

        return $config['module_config']['upload_location'];
    }

}
