<?php

namespace Auth\Form;

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * Description of Login
 *
 * @author seyfer
 */
class LoginForm extends Form
{

    public function __construct()
    {
        parent::__construct(__CLASS__);

        $this->setAttribute("method", "post");
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute("id", __CLASS__);

        $this->addElements();
    }

    protected function addElements()
    {
        $username = new Element\Text('username');
        $username->setLabel('Username');
        $username->setAttribute('required', 'required');
        $this->add($username);

        $email = new Element\Email('email');
        $email->setLabel("Email");
        $email->setAttribute('required', 'required');
        $this->add($email);

        $password = new Element\Password('password');
        $password->setLabel('password');
        $password->setAttribute('required', 'required');
        $this->add($password);

        $remember = new Element\Checkbox('rememberme');
        $remember->setLabel('remember me');
        $this->add($remember);

        $submit = new Element\Submit('submit');
        $submit->setValue("submit")->setLabel("submit");
        $this->add($submit);
    }

}
