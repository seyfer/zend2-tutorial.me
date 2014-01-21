<?php

namespace Application;

/**
 * Description of Debug
 *
 * @author seyfer
 */
class Debug extends \Zend\Debug\Debug {

    public static function dump($var, $label = null, $echo = true)
    {
        $env = getenv('APP_ENV') ? : 'production';

        if ($env != 'production') {
            parent::dump($var, $label, $echo);
        }
    }

}
