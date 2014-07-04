<?php
/**
 * LdcUserProfile Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = array(

    /**
     * Switch to enable/disable profile page
     */
    'is_enabled' => true,
 
    /**
     * URL path at which to mount the profile controller
     */
    'url_path' => '/user/profile',
    
    /**
     * Override of default validation groups to switch editing on/off for specific fields
     */
    'validation_group_override' => array(),

    /**
     * Register extensions by adding them with their service manager key and
     * TRUE as value. Unregister an extension by setting the value to FALSE.
     */
    'registered_extensions' => array(
        'ldc-user-profile_extension_zfcuser' => true,
    )

);

/**
 * You do not need to edit below this line
 */
return array(
    'ldc-user-profile' => $settings,

    'router' => array(
        'routes' => array(
            'ldc-user-profile' => array(
                'options' => array(
                    'route'    => $settings['url_path'],
                ),
            ),
        ),
    ),
    
    'zfc_rbac' => [
        'guards' => [
            'ZfcRbac\Guard\RouteGuard' => [
                'ldc-user-profile' => [ 'member' ],
            ],
        ],
    ],
);
