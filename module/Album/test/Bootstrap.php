<?php

namespace AlbumTest;

use Zend\Loader\AutoloaderFactory;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use RuntimeException;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

/**
 * Test bootstrap, for setting up autoloading
 */
class Bootstrap {

    protected static $serviceManager;

    public static function init()
    {
        $zf2ModulePaths = array(dirname(dirname(__DIR__)));
        if (($path           = static::findParentPath('vendor'))) {
            $zf2ModulePaths[] = $path;
        }
        if (($path = static::findParentPath('module')) !== $zf2ModulePaths[0]) {
            $zf2ModulePaths[] = $path;
        }

        static::initAutoloader();

        // use ModuleManager to load this module and it's dependencies
        $config = array(
            'module_listener_options' => array(
                'module_paths' => $zf2ModulePaths,
            ),
            'modules'                 => array(
                'Album'
            )
        );

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();
        static::$serviceManager = $serviceManager;
    }

    public static function chroot()
    {
        $rootPath = dirname(static::findParentPath('module'));
        chdir($rootPath);
    }

    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

    protected static function initAutoloader()
    {
        $vendorPath = static::findParentPath('vendor');

        $zf2Path = getenv('ZF2_PATH');
        if (!$zf2Path) {
            if (defined('ZF2_PATH')) {
                $zf2Path = ZF2_PATH;
            }
            elseif (is_dir($vendorPath . '/ZF2/library')) {
                $zf2Path = $vendorPath . '/ZF2/library';
            }
            elseif (is_dir($vendorPath . '/zendframework/zendframework/library')) {
                $zf2Path = $vendorPath . '/zendframework/zendframework/library';
            }
        }

        if (!$zf2Path) {
            throw new RuntimeException(
            'Unable to load ZF2. Run `php composer.phar install` or'
            . ' define a ZF2_PATH environment variable.'
            );
        }

        if (file_exists($vendorPath . '/autoload.php')) {
            include $vendorPath . '/autoload.php';
        }

        include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true,
                'namespaces'      => array(
                    __NAMESPACE__       => __DIR__ . '/' . __NAMESPACE__,
//                    'Doctrine\Common'   => __DIR__ . '/vendor/doctrine/common',
//                    'Doctrine\DBAL'     => __DIR__ . '/vendor/doctrine/dbal',
//                    'Symfony\Console'   => __DIR__ . '/vendor/symfony/console',
//                    'DoctrineModule'    => __DIR__ . '/vendor/doctrine/doctrine-module',
//                    'DoctrineORMModule' => __DIR__ . '/vendor/doctrine/doctrine-orm-module',
                ),
            ),
        ));
    }

    protected static function findParentPath($path)
    {
        $dir         = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }

}

Bootstrap::init();
Bootstrap::chroot();

//putenv('ZF2_PATH=' . __DIR__ . '/../../../vendor/ZF2/library');
//include_once __DIR__ . '/../../../init_autoloader.php';
//set_include_path(implode(PATH_SEPARATOR, array(
//    '.',
//    __DIR__ . '/../src',
//    __DIR__ . '/../../DoctrineModule/src',
//    __DIR__ . '/../../DoctrineORMModule/src',
//    __DIR__ . '/../../../vendor',
//    get_include_path(),
//)));
//spl_autoload_register(function($class) {
//    $file     = str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $class) . '.php';
//    if (false === ($realpath = stream_resolve_include_path($file))) {
//        return false;
//    }
//    include_once $realpath;
//});
//$loader = new \Mockery\Loader;
//$loader->register();
