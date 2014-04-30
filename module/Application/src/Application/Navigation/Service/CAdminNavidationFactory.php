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
//            array(
//                "label"  => "Выйти",
//                'route'  => "login/process",
//                'action' => "logout",
//            ),
        );

        return $pages;
    }

}
