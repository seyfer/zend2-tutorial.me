<?php

namespace Users\Form;

use Zend\Form\Element;
use Users\Model\UploadTable;
use Users\Form\BaseForm;

/**
 * Description of UploadForm
 *
 * @author seyfer
 */
class UploadForm extends BaseForm
{

    /**
     *
     * @var UploadTable
     */
    private $uploadTable;

    public function setUploadTable(UploadTable $uploadTable)
    {
        $this->uploadTable = $uploadTable;
    }

    public function __construct($name = null)
    {
        parent::__construct(__CLASS__);
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->init();
    }

    public function init()
    {
        $id = new Element\Hidden('id');
        $this->add($id);

        $label = new Element\Text('label');
        $label->setLabel("label");
        $this->add($label);

        $fileupload = new Element\File('fileupload');
        $fileupload->setLabel("fileupload");
        $this->add($fileupload);

        $submit = new Element\Submit('submit');
        $submit->setValue("submit")->setLabel("submit");
        $this->add($submit);
    }

    public function initSharedUsersSelect($uploadId)
    {
        $upload      = $this->uploadTable->getById($uploadId);
        $sharedUsers = $this->uploadTable->getSharedUsers($uploadId);
        foreach ($sharedUsers as $sharedUser) {
            $userSelected[] = $sharedUser->user_id;
        }

//        \Zend\Debug\Debug::dump($sharedUsers);

        $users = $this->userTable->fetchAll();
        foreach ($users as $user) {
            if ($user->getId() != $upload->getUserId()) {
                $usersOptions[$user->getId()] = $user->getEmail();
            }
        }

        $options = array(
            'label'                     => 'Пользователи',
            'value_options'             => $usersOptions,
            'disable_inarray_validator' => true,
            "attributes"                => array(
                "value" => 0,
            )
        );

        $select = new Element\Select('shared_user_ids');
        $select->setOptions($options);
        $select->setValue($userSelected);
        $select->setAttribute('multiple', 'multiple');
        $this->add($select);
    }

}
