<?php

namespace Users\Form;

use Zend\Form\Element;
use Users\Form\BaseForm;

/**
 * Description of UploadJqueryForm
 *
 * @author seyfer
 */
class UploadJqueryForm extends BaseForm
{

    public function __construct()
    {
        parent::__construct(__CLASS__);
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->init();
    }

    public function init()
    {
        $fileupload = new Element\File('files');
        $fileupload->setLabel("files");
        $fileupload->setAttribute('multiple', 'multiple');
        $this->add($fileupload);

        $button = new Element\Button('start');
        $button->setAttribute("type", 'submit');
        $button->setValue("Start upload")->setLabel("Start upload");
        $this->add($button);

        $button = new Element\Button('cancel');
        $button->setAttribute("type", 'reset');
        $button->setValue("Cancel upload")->setLabel("Cancel upload");
        $this->add($button);

        $button = new Element\Button('delete');
        $button->setAttribute("type", 'button');
        $button->setValue("Delete")->setLabel("Delete");
        $this->add($button);

        $checkbox = new Element\Checkbox('toggle');
        $checkbox->setValue("Toggle")->setLabel("Toggle");
        $checkbox->setAttribute("required", "");
        $this->add($checkbox);
    }

}
