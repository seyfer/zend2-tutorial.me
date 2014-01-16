<?php

namespace Page\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Description of SitemapController
 *
 * @author seyfer
 */
class SitemapController extends AbstractActionController {

    public function indexAction()
    {
        return new ViewModel();
    }

}
