<?php

namespace Page\Controller;

use Page\Model\Page;
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    public function indexAction()
    {
//        $this->serviceLocator->get("");

        $page = new Page();
        $page->exchangeArray(array(
            "title" => "test"
        ));

        $arr = $page->toArray();
        Debug::dump($arr);

        return new ViewModel();
    }

    public function deleteAction()
    {

    }

    public function editAction()
    {

    }

    public function addAction()
    {

    }

}
