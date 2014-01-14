<?php

namespace Page\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Factory;

/**
 * Description of PageForm
 *
 * @author seyfer
 */
class PageForm extends Form {

    public function __construct($name = null, $options = array())
    {
        parent::__construct("page", $options);


        $this->setAttribute("method", "post");
        $this->setAttribute("target", "__blank");
        $this->setAttribute("enctype", "application/x-www-form-urlencoded");
        $this->setAttribute("id", "pageform");

        $this->add(array(
            "name"      => "id",
            "attribute" => array(
                "type" => "hidden",
            )
        ));

        $this->add(
                array(
                    "name"      => "article",
                    "attribute" => array(
                        "type" => "textarea",
                    ),
                    "options"   => array(
                        "label" => "Description"
                    )
                )
        );

        $title = new Element("title");
        $title->setAttribute("type", "text");
        $title->setLabel("Заголовок");

        $this->add($title);

        $this->add(
                array(
                    "name"      => "date",
                    "attribute" => array(
                        "type" => "text",
                    ),
                    "options"   => array(
                        "label" => "Publication Date"
                    )
                )
        );

        $this->add(
                array(
                    "name"      => "submit",
                    "attribute" => array(
                        "type"  => "submit",
                        "value" => "Отправить",
                        "id"    => "submitbutton",
                    ),
                )
        );
    }

}
