<?php

return array(
    'router'       => array(
        'routes' => array(
            'page'    => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'       => '/page[/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'page',
                        'action'     => 'index',
                    ),
                ),
//                'may_terminate' => true,
//                'child_routes'  => array(
//                    'sitemap' => array(
//                        "type"     => "Segment",
//                        "options"  => array(
//                            "route" => "/sitemap"
//                        ),
//                        'defaults' => array(
//                            'controller' => 'sitemap',
//                            'action'     => 'index',
//                        ),
//                    ),
//                ),
            ),
            'sitemap' => array(
                "type"    => "Literal",
                "options" => array(
                    "route"    => "/sitemap",
                    'defaults' => array(
                        'controller' => 'sitemap',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers'  => array(
        'invokables' => array(
            'page'    => 'Page\Controller\IndexController',
            'sitemap' => 'Page\Controller\SitemapController',
//            'Page\Controller\Sitemap' => 'Page\Controller\SitemapController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
