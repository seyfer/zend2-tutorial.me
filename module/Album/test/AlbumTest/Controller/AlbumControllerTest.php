<?php

namespace AlbumTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AlbumControllerTest extends AbstractHttpControllerTestCase {

    public function setUp()
    {
//        $this->setApplicationConfig(
//                include '/var/www/zf2-tutorial/config/application.config.php'
//        );

        $this->setApplicationConfig(
                require 'config/application.config.php'
        );

        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/album');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertMatchedRouteName('album');
    }

}
