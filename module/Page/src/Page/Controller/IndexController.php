<?php

namespace Page\Controller;

use Page\Model\Page,
    Page\Model\PageTable,
    Page\Form\PageForm;
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
        $id = (int) $this->params()->fromRoute("id");

//        Debug::dump($id);
//        exit();

        $this->getPageTable()->deletePage($id);

        $this->redirect()->toUrl("/page");
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute("id");

        Debug::dump($id);

//        if ($id == NULL) {
//            $this->redirect()->toUrl("/page/add");
//        }

        $page = $this->getPageTable()->getPage($id);

        $form = new PageForm();
//        $form->setData($page->toArray());
        $form->bind($page);
        $form->get("submit")->setAttribute("value", "Редактировать");

        if ($this->request->isPost()) {
            $form->setInputFilter($page->getInputFilter());
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $page->exchangeArray($form->getData()->getArrayCopy());
                $this->getPageTable()->savePage($page);

                $this->redirect()->toUrl("/page");
            }
        }

        return new ViewModel(array(
            "form" => $form,
            "id"   => $id,
        ));
    }

    public function addAction()
    {
        $form = new PageForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $page = new Page();

            $form->setInputFilter($page->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $page->exchangeArray($form->getData());
                $this->getPageTable()->savePage($page);

                $this->redirect()->toUrl("/page");
            }
        }

        return new ViewModel(array("form" => $form));
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
