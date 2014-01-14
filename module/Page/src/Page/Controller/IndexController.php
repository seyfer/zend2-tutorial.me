<?php

namespace Page\Controller;

use Page\Model\Page;
use Page\Model\PageTable;
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    /**
     *
     * @var PageTable
     */
    protected $pageTable;

    public function indexAction()
    {

        return new ViewModel(
                array(
            "pages" => $this->getPageTable()->fetchAll()
                )
        );
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

    public function getPageTable()
    {
        if (!$this->pageTable) {
            $serviceMan      = $this->getServiceLocator();
            $this->pageTable = $serviceMan->get("Page\Model\PageTable");
        }

        return $this->pageTable;
    }

}
