<?php

namespace Users\Form;

use Zend\Form\Form,
    Zend\Form\Element;

/**
 * Description of LoginForm
 *
 * @author seyfer
 */
class LoginForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('Register');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $email = new Element\Email('email');
        $email->setLabel("Email");
        $email->setAttribute('required', 'required');
        //StringTrim
        $this->add($email);

        $password = new Element\Password('password');
        $password->setLabel('password');
        $this->add($password);

        $remember = new Element\Checkbox('rememberme');
        $remember->setLabel('remember me');
        $this->add($remember);

        $submit = new Element\Submit('submit');
        $submit->setValue("submit")->setLabel("submit");
        $this->add($submit);
    }

}
