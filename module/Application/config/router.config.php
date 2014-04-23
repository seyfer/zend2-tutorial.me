<?php

return array(
    'router' => array(
        'routes' => array(
            'home'        => array(
                'type'          => 'Zend\Mvc\Router\Http\Literal',
                'options'       => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'       => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults'    => array(),
                        ),
                    ),
                ),
            ),
            'admin'       => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin[/]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Admin',
                        'action'     => 'index',
                    ),
                ),
            ),
            'page'        => array(
                'type'          => 'Literal',
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
            'dalbum'      => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/admin/dalbum[/][:action][/:id]',
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
            'album'       => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/admin/album[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Album\Controller\Album',
                        'action'     => 'index',
                    ),
                ),
            ),
            'sed'         => array(
                'type'          => 'Literal',
                'options'       => array(
                    'route'       => '/admin/sed',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'sedIndex',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => TRUE,
                'child_routes'  => array(
                    'sedAction'   => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'       => '/[:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ),
                            'defaults'    => array(
                                'controller' => 'sedIndex',
                                'action'     => 'index',
                            ),
                        ),
                    ),
                    'sedDocument' => array(
                        'type'          => 'Segment',
                        'options'       => array(
                            'route'       => '/document[/]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ),
                            'defaults'    => array(
                                'controller' => 'sedDocument',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => TRUE,
                        'child_routes'  => array(
                            'sedDocumentType'    => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'       => 'type[/][:action][/:id]',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'     => '[0-9]+',
                                    ),
                                    'defaults'    => array(
                                        'controller' => 'sedDocumentType',
                                        'action'     => 'index',
                                    ),
                                ),
                            ),
                            'sedDocumentActions' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'       => '[:action][/][:id]',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'     => '[0-9]+',
                                    ),
                                    'defaults'    => array(
                                        'controller' => 'sedDocument',
                                        'action'     => 'index',
                                    ),
                                ),
                            ),
                            'Side'               => array(
                                'type'          => 'Segment',
                                'options'       => array(
                                    'route'       => 'side[/]',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'     => '[0-9]+',
                                    ),
                                    'defaults'    => array(
                                        'controller' => 'sedDocumentSide',
                                        'action'     => 'index',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes'  => array(
                                    'Action' => array(
                                        'type'    => 'Segment',
                                        'options' => array(
                                            'route'       => '[:action][/:id]',
                                            'constraints' => array(
                                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                'id'     => '[0-9]+',
                                            ),
                                            'defaults'    => array(
                                                'controller' => 'sedDocumentSide',
                                                'action'     => 'index',
                                            ),
                                        ),
                                    ),
                                    "Role"   => array(
                                        "type"    => "Segment",
                                        "options" => array(
                                            "route"       => "role[/][:action][/:id]",
                                            'constraints' => array(
                                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                'id'     => '[0-9]+',
                                            ),
                                            'defaults'    => array(
                                                'controller' => 'sedDocumentSideRole',
                                                'action'     => 'index',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            "Element"            => array(
                                "type"    => "Segment",
                                "options" => array(
                                    "route"       => "element[/][:action][/:id]",
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'     => '[0-9]+',
                                    ),
                                    'defaults'    => array(
                                        'controller' => 'sedDocumentElement',
                                        'action'     => 'index',
                                    ),
                                ),
                            ),
                            "Counter"            => array(
                                "type"    => "Segment",
                                "options" => array(
                                    "route"       => "counter[/][:action][/:id]",
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'     => '[0-9]+',
                                    ),
                                    'defaults'    => array(
                                        'controller' => 'sedDocumentCounter',
                                        'action'     => 'index',
                                    ),
                                ),
                            ),
                            "Mask"               => array(
                                "type"    => "Segment",
                                "options" => array(
                                    "route"       => "mask[/][:action][/:id]",
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'     => '[0-9]+',
                                    ),
                                    'defaults'    => array(
                                        'controller' => 'sedDocumentMask',
                                        'action'     => 'index',
                                    ),
                                ),
                            ),
                            'Contract'           => array(
                                "type"    => "Segment",
                                "options" => array(
                                    "route"       => "contract[/][:action][/:id]",
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'     => '[0-9]+',
                                    ),
                                    'defaults'    => array(
                                        'controller' => 'sedDocumentContract',
                                        'action'     => 'index',
                                    ),
                                ),
                            ),
                            'Registration'       => array(
                                "type"    => "Segment",
                                "options" => array(
                                    "route"       => "registration[/:action][/][:id]",
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'     => '[0-9]+',
                                    ),
                                    'defaults'    => array(
                                        'controller' => 'sedDocumentRegistration',
                                        'action'     => 'index',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'contact'     => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'       => '/contact',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults'    => array(
                        'controller' => 'contact',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
// new controllers and actions without needing to create a new
// module. Simply drop new controllers in, and you can access them
// using the path /application/:controller/:action
            'application' => array(
                'type'          => 'Literal',
                'options'       => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'       => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults'    => array(),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
