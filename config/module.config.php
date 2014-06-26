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
    'service_manager' => array(
        'factories' => array(
            'ldc-user-profile_module_options' => 'LdcUserProfile\Options\ModuleOptionsFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'LdcUserProfile' => __DIR__ . '/../view',
        ),
    ),
);
