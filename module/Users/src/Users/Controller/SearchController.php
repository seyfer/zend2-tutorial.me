<?php

namespace Users\Controller;

use Users\Controller\BaseController;
use ZendSearch\Lucene;
use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Index;

/**
 * Description of SearchController
 *
 * @author seyfer
 */
class SearchController extends BaseController
{

    public function getIndexLocation()
    {
        // выборка конфигурации из конфигурационных данных модуля
        $config = $this->getServiceLocator()->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        if (!empty($config['module_config']['search_index'])) {
            return $config['module_config']['search_index'];
        } else {
            return FALSE;
        }
    }

    public function generateIndexAction()
    {
        $searchIndexLocation = $this->getIndexLocation();

        $index       = Lucene\Lucene::create($searchIndexLocation);
        $userTable   = $this->getServiceLocator()->get('UserTable');
        $uploadTable = $this->getServiceLocator()->get('UploadTable');
        $allUploads  = $uploadTable->fetchAll();

        foreach ($allUploads as $fileUpload) {
            $uploadOwner = $userTable->getById($fileUpload->getUserId());

            // создание полей lucene
            $fileUploadId = Document\Field::unIndexed(
                            'upload_id', $fileUpload->getId());
            $label        = Document\Field::Text('label', $fileUpload->getLabel());
            $owner        = Document\Field::Text('owner', $uploadOwner->getName());

            // создание нового документа и добавление всех полей
            $indexDoc = new Lucene\Document();
            $indexDoc->addField($label);
            $indexDoc->addField($owner);
            $indexDoc->addField($fileUploadId);
            $index->addDocument($indexDoc);
        }

        $index->commit();

        $response = $this->getResponse();
        $response->setContent("Index Ok");
        return $response;
    }

}
