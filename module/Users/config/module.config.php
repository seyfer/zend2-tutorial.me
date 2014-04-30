<?php

return array(
    'module_config' => array(
        'upload_location' => __DIR__ . '/../../../data/uploads',
    ),
    'controllers'   => array(
        'invokables' => array(
            'Users\Controller\Index'         => 'Users\Controller\IndexController',
            'Users\Controller\Register'      => 'Users\Controller\RegisterController',
            'Users\Controller\Login'         => 'Users\Controller\LoginController',
            'Users\Controller\UserManager'   => 'Users\Controller\UserManagerController',
            'Users\Controller\UploadManager' => 'Users\Controller\UploadManagerController',
            'Users\Controller\GroupChat'     => 'Users\Controller\GroupChatController',
        ),
    ),
    'router'        => array(
        'routes' => array(
            'users'        => array(
                'type'          => 'Literal',
                'options'       => array(
                    // Change this to something specific to your module
                    'route'    => '/users',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Users\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'       => '/[:controller[/][:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults'    => array(
                                '__NAMESPACE__' => 'Users\Controller',
                                'action'        => 'index'
                            ),
                        ),
                    ),
                ),
            ),
            'user-manager' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'       => '/user-manager[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults'    => array(
                        'controller' => 'Users\Controller\UserManager',
                        'action'     => 'index',
                    ),
                ),
            ),
            'uploads'      => array(
                'type'    => 'Segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/uploads[/][:action][/:id]',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Users\Controller',
                        'controller'    => 'Users\Controller\UploadManager',
                        'action'        => 'index',
                    ),
                ),
            ),
            'group-chat'   => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'       => '/group-chat[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults'    => array(
                        'controller' => 'Users\Controller\GroupChat',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager'  => array(
        'template_path_stack' => array(
            'users' => __DIR__ . '/../view',
        ),
    ),
);
