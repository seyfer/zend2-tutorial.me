<?php

namespace Application\Controller;

use Application\Form\ContactForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContactController extends AbstractActionController {

    public function indexAction()
    {
        $form = new ContactForm('contact');

        return new ViewModel(
                array("form" => $form)
        );
    }

}
