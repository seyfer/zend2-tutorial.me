<?php

namespace Users\Form;

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * Description of RegisterForm
 *
 * @author seyfer
 */
class RegisterForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct(__CLASS__);
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $name = new Element\Text('name');
        $name->setLabel("Full Name");
        $this->add($name);

        $email = new Element\Email('email');
        $email->setLabel("Email");
        $email->setAttribute('required', 'required');
        $this->add($email);

        $password = new Element\Password('password');
        $password->setLabel('password');
        $this->add($password);

        $passwordC = new Element\Password('confirm_password');
        $passwordC->setLabel('confirm_password');
        $this->add($passwordC);

        $submit = new Element\Submit('submit');
        $submit->setValue("submit")->setLabel("submit");
        $this->add($submit);
    }

}
