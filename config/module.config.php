<?php
return array(
    'ldc-user-profile' => array(
        'registered_extensions' => array(
            'ldc-user-profile_extension_zfcuser' => true,
        ),
    ),
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
            'ldc-user-profile_service'        => 'LdcUserProfile\Service\ProfileServiceFactory',

            'ldc-user-profile_extension_zfcuser' => 'LdcUserProfile\Extensions\ZfcUser\ZfcUserExtensionFactory',
            'ldc-user-profile_extension_zfcuser_fieldset' => 'LdcUserProfile\Extensions\ZfcUser\ZfcUserFieldsetFactory',
            'ldc-user-profile_extension_zfcuser_inputfilter' => 'LdcUserProfile\Extensions\ZfcUser\ZfcUserInputFilterFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'LdcUserProfile' => __DIR__ . '/../view',
        ),
    ),
);
