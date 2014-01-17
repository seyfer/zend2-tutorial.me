<?php

return array(
    'router'       => array(
        'routes' => array(
            'page'    => array(
                'type'          => 'Segment',
                'options'       => array(
                    'route'       => '/page',
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
            'sitemap' => array(
                "type"        => "Literal",
                "options"     => array(
                    "route" => "/sitemap"
                ),
                'constraints' => array(
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults'    => array(
                    'controller' => 'sitemap',
                    'action'     => 'index',
                ),
            ),
        ),
    ),
    'controllers'  => array(
        'invokables' => array(
            'page'    => 'Page\Controller\IndexController',
            'sitemap' => 'Page\Controller\SitemapController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
