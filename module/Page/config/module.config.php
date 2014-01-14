<?php

return array(
    'router'       => array(
        'routes' => array(
            'page' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'       => '/page[/:action][/:id]',
                    'constraints' => array(
//                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Page\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers'  => array(
        'invokables' => array(
            'Page\Controller\Index' => 'Page\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
