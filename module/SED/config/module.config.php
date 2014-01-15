<?php

return array(
    'router'       => array(
        'routes' => array(
            'sed' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'       => '/sed[/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'main',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers'  => array(
        'invokables' => array(
            'main' => 'SED\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
