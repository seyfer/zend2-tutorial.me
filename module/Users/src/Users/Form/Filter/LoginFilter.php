<?php

namespace Users\Form\Filter;

use Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface,
    Zend\InputFilter\Input;
use Zend\Validator;

/**
 * Description of LoginFilter
 *
 * @author seyfer
 */
class LoginFilter extends InputFilter implements
InputFilterAwareInterface, InputFilterInterface
{

    public function __construct()
    {
        $this->getInputFilter();
    }

    public function getInputFilter()
    {
        $email = new Input('email');
        $email->setRequired(TRUE);
        $email->getValidatorChain()->attach(new Validator\EmailAddress());
        $this->add($email);

        $password = new Input('password');
        $password->setRequired(TRUE);
        $this->add($password);

        return $this;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        return false;
    }

}
