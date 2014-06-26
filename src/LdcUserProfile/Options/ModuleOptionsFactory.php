<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfile\Options;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleOptionsFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        return new ModuleOptions(isset($config['ldc-user-profile']) ? $config['ldc-user-profile'] : array());
    }
}
