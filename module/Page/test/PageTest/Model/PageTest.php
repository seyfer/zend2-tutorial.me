<?php

namespace PageTest\Model;

use Page\Model\Page;
use PHPUnit_Framework_TestCase;

/**
 * Description of PageTest
 *
 * @author seyfer
 */
class PageTest extends PHPUnit_Framework_TestCase {

    public function testPageInitialState()
    {
        $album = new Page();

        $this->assertNull(
                $album->article, '"artist" should initially be null'
        );
        $this->assertNull(
                $album->id, '"id" should initially be null'
        );
        $this->assertNull(
                $album->title, '"title" should initially be null'
        );
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $album = new Page();
        $data  = array('article' => 'some artist',
            'id'      => 123,
            'title'   => 'some title');

        $album->exchangeArray($data);

        $this->assertSame(
                $data['article'], $album->article, '"artist" was not set correctly'
        );
        $this->assertSame(
                $data['id'], $album->id, '"id" was not set correctly'
        );
        $this->assertSame(
                $data['title'], $album->title, '"title" was not set correctly'
        );
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $album = new Page();
        $data  = array('article' => 'some artist',
            'id'      => 123,
            'title'   => 'some title');

        $album->exchangeArray($data);
        $copyArray = $album->getArrayCopy();

        $this->assertSame(
                $data['article'], $copyArray['article'], '"artist" was not set correctly'
        );
        $this->assertSame(
                $data['id'], $copyArray['id'], '"id" was not set correctly'
        );
        $this->assertSame(
                $data['title'], $copyArray['title'], '"title" was not set correctly'
        );
    }

    public function testInputFiltersAreSetCorrectly()
    {
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );

        $album = new Page();

        $inputFilter = $album->getInputFilter();

        $this->assertSame(3, $inputFilter->count());
        $this->assertTrue($inputFilter->has('artist'));
        $this->assertTrue($inputFilter->has('id'));
        $this->assertTrue($inputFilter->has('title'));
    }

}
