<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'extension-module_extension' => 'ExtensionModule\ExtensionFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'ExtensionModule' => __DIR__ . '/../view',
        ),
    ),
);
