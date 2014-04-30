<?php

namespace Application\Navigation\Service;

use Zend\Navigation\Service\ConstructedNavigationFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * Description of CIndexNavidationFactory
 *
 * @author seyfer
 */
class CIndexNavidationFactory extends ConstructedNavigationFactory
{

    /**
     *
     * @var ServiceManager
     */
    private $serviceManager;

    public function __construct($config = null, ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        $config = $config ? $config : $this->getDefaultPages();
        parent::__construct($config);
    }

    public function getName()
    {
        return "index";
    }

    protected function getDefaultPages()
    {
        $pages = array(
            array(
                'label' => 'Главная',
                'route' => 'home',
            ),
            array(
                'label'   => "Авторизован",
                'route'   => "success",
                'visible' => FALSE,
//                'class'   => "not-display",
            ),
            array(
                'label' => "Users route",
                'route' => 'users',
                'pages' => array(
                    array(
                        'label'      => "Users login",
                        'route'      => 'users/default',
                        'action'     => 'index',
                        'controller' => 'login'
                    ),
                    array(
                        'label'      => "Users register",
                        'route'      => 'users/default',
                        'action'     => 'index',
                        'controller' => 'register'
                    ),
                )
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
                'label' => "Group chat",
                'route' => 'group-chat',
            ),
        );

        $username = $this->getUsername();

        if (!$username) {

            $pages[] = array(
                "label"      => "Войти",
                'route'      => "users/default",
                'action'     => 'index',
                'controller' => 'login',
//                "pages"  => array(
//                    array(
//                        "label"  => "Войти",
//                        'route'  => "login/login",
//                        'action' => 'login',
//                    ),
//                )
            );
        }
//        else {
//
//            $pages[] = array(
//                'label' => 'Админка',
//                'route' => 'admin',
//            );
//
//            $pages[] = array(
//                "label"  => "Выйти",
//                'route'  => "login/process",
//                'action' => "logout",
//            );
//        }

        return $pages;
    }

    protected function getUsername()
    {
        $store    = $this->serviceManager->get('AuthServiceUsers')->getStorage();
        $username = $store->read();

        return $username;
    }

}
