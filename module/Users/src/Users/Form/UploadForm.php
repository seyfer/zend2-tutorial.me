<?php

namespace Users\Form;

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * Description of UploadForm
 *
 * @author seyfer
 */
class UploadForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct(__CLASS__);
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

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

}
