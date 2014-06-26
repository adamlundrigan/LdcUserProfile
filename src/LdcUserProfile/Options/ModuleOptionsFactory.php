<?php
namespace LdcUserProfile\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use LdcUserProfile\Options\ModuleOptions;

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