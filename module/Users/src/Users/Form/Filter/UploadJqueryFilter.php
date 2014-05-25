<?php

namespace Users\Form\Filter;

use Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface,
    Zend\InputFilter\Input;
use Zend\Validator;

/**
 * Description of UploadJqueryFilter
 *
 * @author seyfer
 */
class UploadJqueryFilter extends InputFilter implements
InputFilterAwareInterface
{

    public function __construct()
    {
        $this->getInputFilter();
    }

    public function getInputFilter()
    {
        $toggle = new Input('toggle');
        $toggle->setRequired(FALSE);
        $this->add($toggle);

        $files = new \Zend\InputFilter\FileInput('files');
        $files->setRequired(TRUE);
        $files->getValidatorChain()->attach(new Validator\File\UploadFile);
        $files->getFilterChain()->attach(new \Zend\Filter\File\RenameUpload(array(
            'target'    => __DIR__ . '/../../../../../../tmpuploads/tmp',
            'randomize' => true,
        )));
        $this->add($files);

        return $this;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        return false;
    }

}
