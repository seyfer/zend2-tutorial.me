<?php

namespace Album\Controller\Doctrine;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;
use Album\Entity\Album,
    Album\Form\AlbumForm;
use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator as ZendPaginator;

/**
 * Description of IndexController
 *
 * @author seyfer
 */
class IndexController extends AbstractActionController {

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    private function allAlbums($id, $column, $order)
    {
        $repository = $this->getEntityManager()->getRepository('Album\Entity\Album');

        $queryBuilder = $repository->createQueryBuilder('album');
        $queryBuilder->distinct();
//        $queryBuilder->select('album');
//        $queryBuilder->join('Category\Entity\CategoryName', 'category_name', 'WITH', 'category.id = category_name.category');
//        $queryBuilder->orderBy("category.status");
        $q            = $queryBuilder->getDql();

        return $query = $this->getEntityManager()->createQuery($q);
    }

    public function indexAction()
    {
        $query     = $this->allAlbums($id, $column, $order);
        $paginator = new ZendPaginator(new PaginatorAdapter(new ORMPaginator($query)));
        $paginator->setDefaultItemCountPerPage(10);

        $page = (int) $this->params()->fromQuery('page');
        if ($page) {
            $paginator->setCurrentPageNumber($page);
        }

        return new ViewModel(array(
//            'albums' => $this->getEntityManager()->getRepository('Album\Entity\Album')
//                    ->findAll(),
            'paginator' => $paginator
        ));
    }

    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setAttribute('label', 'Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $album = new Album();

            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $album->exchangeArray($form->getData());
                $this->getEntityManager()->persist($album);
                $this->getEntityManager()->flush();

                // Redirect to list of albums
                return $this->redirect()->toRoute('dalbum');
            }
        }

        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');

        if (!$id) {
            return $this->redirect()->toRoute('dalbum', array('action' => 'add'));
        }

        $album = $this->getEntityManager()->find('Album\Entity\Album', $id);

        $form = new AlbumForm();
        $form->setBindOnValidate(false);
        $form->bind($album);
        $form->get('submit')->setAttribute('label', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->bindValues();
                $this->getEntityManager()->flush();

                // Redirect to list of albums
                return $this->redirect()->toRoute('dalbum');
            }
        }

        return array(
            'id'   => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');

        if (!$id) {
            return $this->redirect()->toRoute('dalbum');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');

                $album = $this->getEntityManager()
                        ->find('Album\Entity\Album', $id);

                if ($album) {
                    $this->getEntityManager()->remove($album);
                    $this->getEntityManager()->flush();
                }
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('dalbum', array(
                        'action' => 'index',
            ));
        }

        return array(
            'id'    => $id,
            'album' => $this->getEntityManager()->find('Album\Entity\Album', $id)
        );
    }

}
