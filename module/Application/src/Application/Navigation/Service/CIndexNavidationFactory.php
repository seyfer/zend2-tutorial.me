<?php

namespace Application\Navigation\Service;

use Zend\Navigation\Service\ConstructedNavigationFactory;

/**
 * Description of CIndexNavidationFactory
 *
 * @author seyfer
 */
class CIndexNavidationFactory extends ConstructedNavigationFactory
{

    public function __construct($config = null)
    {
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
                        'label'  => "Users login",
                        'route'  => 'user-manager',
                        'action' => 'edit',
                    ),
                ),
            ),
        );

        $username = $this->getUsername();

        if (!$username) {

            $pages[] = array(
                "label" => "Войти",
                'route' => "login",
                "pages" => array(
                    array(
                        "label"  => "Войти",
                        'route'  => "login/login",
                        'action' => 'login',
                    ),
                )
            );
        } else {

            $pages[] = array(
                'label' => 'Админка',
                'route' => 'admin',
            );

            $pages[] = array(
                "label"  => "Выйти",
                'route'  => "login/process",
                'action' => "logout",
            );
        }

        return $pages;
    }

    protected function getUsername()
    {
        $store    = new \Auth\Model\AuthStorage("auth_storage");
        $username = $store->read();

        return $username;
    }

}
