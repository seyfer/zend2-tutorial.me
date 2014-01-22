<?php

namespace Application\Navigation\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

/**
 * Description of IndexNavigationFactory
 *
 * @author seyfer
 */
class IndexNavigationFactory extends DefaultNavigationFactory {

    protected function getName()
    {
        return 'index';
    }

    

}
