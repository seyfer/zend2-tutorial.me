<?php

namespace Page\Model;

use Zend\Debug\Debug;
use Zend\Form\Annotation;

/**
 * Description of User
 *
 * @author seyfer
 *
 * Аннотации для теста
 * @Annotation\Name("user")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 */
class User {

    /**
     *
     * @var type
     * @Annotation\Filter({"name":"StringTrim"});
     * @Annotation\Filter({"name":"StripTags"});
     * @Annotation\Validator({"name":"StringLength", "options":{"min":3, "max":100}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Login:"})
     */
    public $login;

    /**
     *
     * @var type
     * @Annotation\Filter({"name":"StringTrim"});
     * @Annotation\Validator({"name":"StringLength", "options":{"min":6, "max":100}})
     * @Annotation\Attributes({"type":"password"})
     * @Annotation\Options({"label":"Password:"})
     */
    public $password;

    /**
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Options({"label":"Remember Me ?:"})
     */
    public $rememberme;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Login"})
     */
    public $submit;

}
