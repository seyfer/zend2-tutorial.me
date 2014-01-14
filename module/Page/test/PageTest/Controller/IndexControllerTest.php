<?php

namespace Page\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Description of IndexControllerTest
 *
 * @author seyfer
 */
class IndexControllerTest extends AbstractHttpControllerTestCase {

    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
                require 'config/application.config.php'
        );

        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/page');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Page');
        $this->assertControllerName('Page\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('page');
    }

}
