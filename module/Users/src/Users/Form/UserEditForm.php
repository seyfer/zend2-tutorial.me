<?php

namespace Users\Form;

use Zend\Form\Form,
    Zend\Form\Element;

/**
 * Description of UserEditForm
 *
 * @author seyfer
 */
class UserEditForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct(__CLASS__);
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');


        $name = new Element\Hidden('id');
        $this->add($name);

        $name = new Element\Text('name');
        $name->setLabel("Full Name");
        $this->add($name);

        $email = new Element\Email('email');
        $email->setLabel("Email");
        $email->setAttribute('required', 'required');
        $this->add($email);

        $submit = new Element\Submit('submit');
        $submit->setValue("submit")->setLabel("submit");
        $this->add($submit);
    }

}
