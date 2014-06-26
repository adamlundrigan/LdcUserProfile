<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'LdcUserProfile\Controller\Profile' => 'LdcUserProfile\Controller\ProfileController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'ldc-user-profile' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/user/profile',
                    'defaults' => array(
                        'controller' => 'LdcUserProfile\Controller\Profile',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'LdcUserProfile' => __DIR__ . '/../view',
        ),
    ),
);
