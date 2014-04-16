<?php

namespace Users\Form\Filter;

use Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface,
    Zend\InputFilter\Input;
use Zend\Validator;

/**
 * Description of RegisterFilter
 *
 * @author seyfer
 */
class RegisterFilter extends InputFilter implements
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

        $name = new Input('name');
        $name->setRequired(TRUE);
        $name->getFilterChain()->attach(new \Zend\Filter\StringTrim());
        $name->getValidatorChain()->attach(new Validator\StringLength(array(
            'encoding' => 'UTF-8',
            'min'      => 2,
            'max'      => 140,
        )));
        $this->add($name);

        $password = new Input('password');
        $password->setRequired(TRUE);
        $this->add($password);

        $passwordC = new Input('confirm_password');
        $passwordC->setRequired(TRUE);
        $this->add($passwordC);

        return $this;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        return false;
    }

}
