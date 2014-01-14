<?php

namespace Page\Model;

use Zend\Debug\Debug;

/**
 * Description of Page
 *
 * @author seyfer
 */
class Page {

    public $id;
    public $title;
    public $article;
    public $date;
    public $bug;

    /**
     *
     * @param array $data
     */
    public function exchangeArray(array $data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function toArray()
    {
        $class = new \ReflectionClass(__CLASS__);
        $props = $class->getProperties();

        $arr = [];
        foreach ($props as $prop) {
//            Debug::dump($prop);

            $arr[$prop->name] = $this->{$prop->name};
        }

        return $arr;
    }

}
