<?php

namespace Application\Navigation\Service;

use Zend\Navigation\Service\ConstructedNavigationFactory;

/**
 * Description of CIndexNavidationFactory
 *
 * @author seyfer
 */
class CAdminNavidationFactory extends ConstructedNavigationFactory {

    public function __construct($config = null)
    {
        $config = $config ? $config : $this->getDefaultPages();
        parent::__construct($config);
    }

    public function getName()
    {
        return "admin";
    }

    protected function getDefaultPages()
    {
        $pages = array(
            array(
                'label' => 'Админка',
                'route' => 'admin',
            ),
            array(
                'label' => 'Документы',
                'route' => 'sed/sedDocument',
                'pages' => array(
                    array(
                        'label'   => 'Добавить',
                        'route'   => 'sed/sedDocument/sedDocumentActions',
                        'action'  => 'add',
                        'visible' => FALSE,
                    ),
                    array(
                        'label'   => 'Редактировать',
                        'route'   => 'sed/sedDocument/sedDocumentActions',
                        'action'  => 'edit',
                        'visible' => FALSE,
                    ),
                    array(
                        'label'   => 'Удалить',
                        'route'   => 'sed/sedDocument/sedDocumentActions',
                        'action'  => 'delete',
                        'visible' => FALSE,
                    ),
                    array(
                        'label'   => 'Просмотр',
                        'route'   => 'sed/sedDocument/sedDocumentActions',
                        'action'  => 'view',
                        'visible' => FALSE,
                    ),
                    array(
                        'label'   => 'Создать версию',
                        'route'   => 'sed/sedDocument/sedDocumentActions',
                        'action'  => 'version',
                        'visible' => FALSE,
                    ),
                    array(
                        'label' => 'Контракты GATE',
                        'route' => 'sed/sedDocument/Contract',
                    ),
                    array(
                        'label' => 'Регистрация',
                        'route' => 'sed/sedDocument/Registration',
                    ),
                    array(
                        'label' => 'Счетчики',
                        'route' => 'sed/sedDocument/Counter',
                        'pages' => array(
                            array(
                                'label'   => 'Добавить',
                                'route'   => 'sed/sedDocument/Counter',
                                'action'  => 'add',
                                'visible' => FALSE,
                            ),
                            array(
                                'label'   => 'Редактировать',
                                'route'   => 'sed/sedDocument/Counter',
                                'action'  => 'edit',
                                'visible' => FALSE,
                            ),
                            array(
                                'label'   => 'Удалить',
                                'route'   => 'sed/sedDocument/Counter',
                                'action'  => 'delete',
                                'visible' => FALSE,
                            ),
                        ),
                    ),
                    array(
                        'label' => 'Маски',
                        'route' => 'sed/sedDocument/Mask',
                        'pages' => array(
                            array(
                                'label'   => 'Добавить',
                                'route'   => 'sed/sedDocument/Mask',
                                'action'  => 'add',
                                'visible' => FALSE,
                            ),
                            array(
                                'label'   => 'Редактировать',
                                'route'   => 'sed/sedDocument/Mask',
                                'action'  => 'edit',
                                'visible' => FALSE,
                            ),
                            array(
                                'label'   => 'Удалить',
                                'route'   => 'sed/sedDocument/Mask',
                                'action'  => 'delete',
                                'visible' => FALSE,
                            ),
                        ),
                    ),
                ),
            ),
            array(
                'label' => 'Типы документов',
                'route' => 'sed/sedDocument/sedDocumentType',
                'pages' => array(
                    array(
                        'label'   => 'Добавить',
                        'route'   => 'sed/sedDocument/sedDocumentType',
                        'action'  => 'add',
                        'visible' => FALSE,
                    ),
                    array(
                        'label'   => 'Редактировать',
                        'route'   => 'sed/sedDocument/sedDocumentType',
                        'action'  => 'edit',
                        'visible' => FALSE,
                    ),
                    array(
                        'label'   => 'Удалить',
                        'route'   => 'sed/sedDocument/sedDocumentType',
                        'action'  => 'delete',
                        'visible' => FALSE,
                    ),
                    array(
                        'label' => 'Элементы',
                        'route' => 'sed/sedDocument/Element',
                        'pages' => array(
                            array(
                                'label'   => 'Добавить',
                                'route'   => 'sed/sedDocument/Element',
                                'action'  => 'add',
                                'visible' => FALSE,
                            ),
                            array(
                                'label'   => 'Редактировать',
                                'route'   => 'sed/sedDocument/Element',
                                'action'  => 'edit',
                                'visible' => FALSE,
                            ),
                            array(
                                'label'   => 'Удалить',
                                'route'   => 'sed/sedDocument/Element',
                                'action'  => 'delete',
                                'visible' => FALSE,
                            ),
                        ),
                    ),
                ),
            ),
            array(
                'label' => 'Роли сторон',
                'route' => 'sed/sedDocument/Side/Role',
                'pages' => array(
                    array(
                        'label'   => 'Добавить',
                        'route'   => 'sed/sedDocument/Side/Role',
                        'action'  => 'add',
                        'visible' => FALSE,
                    ),
                    array(
                        'label'   => 'Редактировать',
                        'route'   => 'sed/sedDocument/Side/Role',
                        'action'  => 'edit',
                        'visible' => FALSE,
                    ),
                    array(
                        'label'   => 'Удалить',
                        'route'   => 'sed/sedDocument/Side/Role',
                        'action'  => 'delete',
                        'visible' => FALSE,
                    ),
                    array(
                        'label'   => 'Информация о сторонах',
                        'route'   => 'sed/sedDocument/Side',
                        'visible' => TRUE,
                    ),
                ),
            ),
            array(
                'label' => 'На главную',
                'route' => 'home',
            ),
            array(
                "label"  => "Выйти",
                'route'  => "login/process",
                'action' => "logout",
            ),
        );

        return $pages;
    }

}

//            array(
//                'label' => 'Страницы',
//                'route' => 'page',
//                'pages' => array(
//                    array(
//                        'label'  => 'Добавить',
//                        'route'  => 'page/actions',
//                        'action' => 'add',
//                    ),
//                    array(
//                        'label'  => 'Редактировать',
//                        'route'  => 'page/actions',
//                        'action' => 'edit',
//                    ),
//                    array(
//                        'label'  => 'Удалить',
//                        'route'  => 'page/actions',
//                        'action' => 'delete',
//                    ),
//                ),
//            ),
//            array(
//                'label' => 'Альбомы',
//                'route' => 'album',
//                'pages' => array(
//                    array(
//                        'label'  => 'Добавить',
//                        'route'  => 'album',
//                        'action' => 'add',
//                    ),
//                    array(
//                        'label'  => 'Редактировать',
//                        'route'  => 'album',
//                        'action' => 'edit',
//                    ),
//                    array(
//                        'label'  => 'Удалить',
//                        'route'  => 'album',
//                        'action' => 'delete',
//                    ),
//                ),
//            ),
