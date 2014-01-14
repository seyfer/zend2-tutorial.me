<?php

namespace Page\Model;

use Zend\Debug\Debug;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Description of Page
 *
 * @author seyfer
 */
class Page implements InputFilterAwareInterface {

    public $id;
    public $title;
    public $article;
    public $date;

    /**
     *
     * @var InputFilterInterface
     */
    protected $inputFilter;

    /**
     *
     * @param array $data
     */
//    public function exchangeArray(array $data)
//    {
//        foreach ($data as $key => $value) {
//            $this->{$key} = $value;
//        }
//    }

    public function exchangeArray(array $data)
    {
        $this->id      = $data['id'];
        $this->title   = $data['title'];
        $this->article = $data['article'];
        $this->date    = $data['date'];
    }

    public function getArrayCopy()
    {
        $class = new \ReflectionClass(__CLASS__);
        $props = $class->getProperties();

        $arr = [];
        foreach ($props as $prop) {
//            Debug::dump($prop);
            if ($prop->isPublic())
                $arr[$prop->name] = $this->{$prop->name};
        }

        return $arr;
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter;
            $factory     = new InputFactory;

            $inputFilter->add($factory->createInput(
                            array(
                                "name"     => "id",
                                "required" => true,
                                "filter"   => array(
                                    array("name" => "Int"),
                                ),
                            )
                    )
            );

            $inputFilter->add($factory->createInput(
                            array(
                                "name"       => "article",
                                "required"   => true,
                                "filter"     => array(
                                    array("name" => "StripTags"),
                                    array("name" => "StringTrim"),
                                ),
                                "validators" => array(
                                    array(
                                        "name"    => "StringLength",
                                        "options" => array(
                                            "encoding" => "UTF-8",
                                            "min"      => "10",
                                            "max"      => "10000",
                                        ),
                                    ),
                                ),
                            )
                    )
            );

            $inputFilter->add($factory->createInput(
                            array(
                                "name"       => "title",
                                "required"   => true,
                                "filter"     => array(
                                    array("name" => "StripTags"),
                                    array("name" => "StringTrim"),
                                ),
                                "validators" => array(
                                    array(
                                        "name"    => "StringLength",
                                        "options" => array(
                                            "encoding" => "UTF-8",
                                            "min"      => "10",
                                            "max"      => "100",
                                        ),
                                    ),
                                ),
                            )
                    )
            );

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {

    }

}
