<?php

namespace Page\Test\Model;

use Page\Model\PageTable;
use Page\Model\Page;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

/**
 * Description of PageTableTest
 *
 * @author seyfer
 */
class PageTableTest extends PHPUnit_Framework_TestCase {

    public function testFetchAllReturnsAllAlbums()
    {
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );

        $resultSet = new ResultSet();

        $mockTableGateway = $this->getMock(
                'Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false
        );

        $mockTableGateway->expects($this->once())
                ->method('select')
                ->with()
                ->will($this->returnValue($resultSet));

        $mockAdapter = $this->getMock('Zend\Db\Adapter\Adapter', array(), array(
            "Zend\Db\Adapter\Driver\Mysqli"
        ));


        $mockTableGateway->setAdapter($mockAdapter);

//        $pageTable = new PageTable($mockTableGateway->getAdapter());
        $pageTable = new PageTable($mockAdapter);

        $this->assertSame($resultSet, $pageTable->fetchAll());
    }

}
