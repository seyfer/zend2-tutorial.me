<?php

namespace Page\Controller;

use Page\Model\Page,
    Page\Model\User,
    Page\Model\PageTable,
    Page\Form\PageForm,
    Page\Model\Acl;
use Zend\Debug\Debug,
    Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\Form\Annotation\AnnotationBuilder,
    Zend\Session\SessionManager,
    Zend\Session\Config\StandardConfig,
    Zend\Session\Container;
use Application\Model\MyAdapter;

class IndexController extends AbstractActionController {

    /**
     *
     * @var PageTable
     */
    protected $pageTable;

    /**
     *
     * @var Acl
     */
    protected $acl;

    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $this->acl = (new Acl())->getAcl();

//        if (!$this->getServiceLocator()
//                        ->get('AuthService')->hasIdentity()) {
//            return $this->redirect()->toRoute('login');
//        }

        return parent::onDispatch($e);
    }

    /**
     * тестирование билдера
     * @return type
     */
    public function contactAction()
    {
        $builder = new AnnotationBuilder();
        $form    = $builder->createForm("Page\Model\Page");

        return new ViewModel(array(
            "form" => $form,
        ));
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return new ViewModel(
                array(
            "pages" => $this->getPageTable()->fetchAll()
                )
        );
    }

    /**
     *
     * @return type
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute("id");

//        Debug::dump($id);
//        exit();

        if ($id == NULL) {
            $this->redirect()->toUrl("/page");
        }

        $page = $this->getPageTable()->getPage($id);

        if ($this->request->isPost()) {
            $del = $this->request->getPost("del");

            if ($del == "Yes") {
                $this->getPageTable()->deletePage($id);
                $this->redirect()->toRoute("page");
            }
        }

        return array(
            "id"   => $id,
            "page" => $page,
        );
    }

    /**
     *
     * @return type
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute("id");

//        Debug::dump($id);
        if ($id == NULL) {
            $this->redirect()->toRoute("page/actions", array("action" => "add"));
        }

        $page = $this->getPageTable()->getPage($id);

        $form = new PageForm();
//        $form->setData($page->toArray());
        $form->bind($page);
        $form->get("submit")->setAttribute("value", "Редактировать");

        if ($this->request->isPost()) {
            $form->setInputFilter($page->getInputFilter());
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                //возвращает Page, т.к. выше bind
                $page->exchangeArray($form->getData()->getArrayCopy());
                $this->getPageTable()->savePage($page);

                $this->redirect()->toRoute("page");
            }
        }

        return array(
            "form" => $form,
            "id"   => $id,
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
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

                $this->redirect()->toRoute("page");
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
