<?php

namespace Application\Navigation\Service;

use Zend\Navigation\Service\ConstructedNavigationFactory;

/**
 * Description of CIndexNavidationFactory
 *
 * @author seyfer
 */
class CAdminNavidationFactory extends ConstructedNavigationFactory
{

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
                'label' => 'На главную',
                'route' => 'home',
            ),            
            array(
                'label' => "User manager",
                'route' => 'user-manager',
                'pages' => array(
                    array(
                        'label'   => "Users edit",
                        'route'   => 'user-manager',
                        'visible' => FALSE,
                        'action'  => 'edit',
                    ),
                ),
            ),
            array(
                'label' => "Upload manager",
                'route' => 'uploads',
                'pages' => array(
                    array(
                        'label'   => "Upload",
                        'route'   => 'uploads',
                        'visible' => TRUE,
                        'action'  => 'upload',
                    ),
                ),
            ),
            array(
                'label' => "Media manager",
                'route' => 'media',
                'pages' => array(
                    array(
                        'label'   => "Upload",
                        'route'   => 'media',
                        'visible' => TRUE,
                        'action'  => 'upload',
                    ),
                ),
            ),
            array(
                'label' => "Group chat",
                'route' => 'group-chat',
            ),
            array(
                'label'      => "Search document",
                'route'      => 'users/default',
                'controller' => 'search',
                'pages'      => array(
                    array(
                        'label'      => "Generate index",
                        'route'      => 'users/default',
                        'visible'    => TRUE,
                        'action'     => 'generateIndex',
                        'controller' => 'search',
                    ),
                ),
            ),
//            array(
//                "label"  => "Выйти",
//                'route'  => "login/process",
//                'action' => "logout",
//            ),
        );

        return $pages;
    }

}
