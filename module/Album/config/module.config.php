<?php

namespace Album;

return array(
    'controllers'  => array(
        'invokables' => array(
            'Album\Controller\Album' => 'Album\Controller\AlbumController',
            'AlbumDoc'               => 'Album\Controller\Doctrine\IndexController',
        ),
    ),
    'router'       => array(
        'routes' => array(
            'dalbum' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/dalbum[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'AlbumDoc',
                        'action'     => 'index',
                    ),
                ),
            ),
            'album'  => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/album[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Album\Controller\Album',
                        'action'     => 'index',
                    ),
                ),
//                'may_terminate' => true,
//                'child_routes'  => array(
//                    'albumd' => array(
//                        'type'    => 'literal',
//                        'options' => array(
//                            'route'       => '/album/doc',
//                            'constraints' => array(
//                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                'id'     => '[0-9]+',
//                            ),
//                            'defaults'    => array(
//                                'controller' => 'Album\Controller\Doctrine\Index',
//                                'action'     => 'index',
//                            ),
//                        ),
//                    ),
//                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'album' => __DIR__ . '/../view',
        ),
    ),
    'doctrine'     => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default'             => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);
