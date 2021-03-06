<?php

namespace Application\Navigation;

/**
 * Description of Injecter
 *
 * @author seyfer
 */
class Injecter {

    //            $navigation = new \Zend\Navigation\Navigation($pages);
    //            $router     = $sm->get('router');
    //            $module     = new \Application\Navigation\Injecter();
    //            $navigation = $module->injectRouter($navigation, $router);
    public function injectRouter($navigation, $router)
    {
        foreach ($navigation->getPages() as $page) {
//            var_export($page);
            if ($page instanceof \Zend\Navigation\Page\Mvc) {
                $page->setDefaultRouter($router);
            }

            if ($page->hasPages()) {
                $this->injectRouter($page, $router);
            }
        }

        return $navigation;
    }

}

?>
