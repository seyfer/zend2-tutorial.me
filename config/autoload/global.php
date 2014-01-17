<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return array(
    "db"              => array(
        "driver"   => "Mysqli",
        "database" => "zend2_tutorial",
        'options'  => array('buffer_results' => true)
    ),
    'admin'           => array(
        "admin_layout_template" => "layout/admin",
    ),
    'service_manager' => array(
        "factories" => array(
            'Zend\Db\Adapter\Adapter' => "Zend\Db\Adapter\AdapterServiceFactory"
        )
    ),
    'session'         => array(
        'config'     => array(
            'class'   => 'Zend\Session\Config\SessionConfig',
            'options' => array(
                'name' => 'myapp',
            ),
        ),
        'storage'    => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            'Zend\Session\Validator\RemoteAddr',
            'Zend\Session\Validator\HttpUserAgent',
        ),
    ),
);
