<?php

namespace Application\Navigation\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

/**
 * Description of AdminNavigationFactory
 *
 * @author seyfer
 */
class AdminNavigationFactory extends DefaultNavigationFactory {

    protected function getName()
    {
        return 'admin';
    }

}
