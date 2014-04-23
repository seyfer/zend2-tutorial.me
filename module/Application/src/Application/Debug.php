<?php

namespace Application;

use Doctrine\Common\Util\Debug as DoctrineDebug;

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

    public static function dumpLevel($var, $maxDepth = 2)
    {
        $env = getenv('APP_ENV') ? : 'production';

        if ($env != 'production') {
            echo '<pre>';
            DoctrineDebug::dump($var, $maxDepth);
            echo '</pre>';
        }
    }

}
