<?php

return array(
    'router'       => array(
        'routes' => array(
            'page' => array(
                'type'          => 'Segment',
                'options'       => array(
                    'route'       => '/admin/page',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults'    => array(
                        'controller' => 'page',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'actions' => array(
                        "type"        => "Segment",
                        "options"     => array(
                            "route" => "[/:action][/][:id]"
                        ),
                        'constraints' => array(
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id'         => '[0-9]+',
                        ),
                        'defaults'    => array(
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers'  => array(
        'invokables' => array(
            'page' => 'Page\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
