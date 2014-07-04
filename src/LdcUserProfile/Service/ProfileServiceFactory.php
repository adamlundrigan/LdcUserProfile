<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfile\Service;

use LdcUserProfile\Extensions\AbstractExtension;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProfileServiceFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new ProfileService();

        // Get the module options
        $moduleOptions = $serviceLocator->get('ldc-user-profile_module_options');
        $service->setModuleOptions($moduleOptions);

        // Register/Unregister the active/inactive extensions
        foreach ($moduleOptions->getRegisteredExtensions() as $extensionName => $isActive) {
            if ($isActive) {
                $this->registerExtension($extensionName, $service, $serviceLocator);
            } else {
                $service->unregisterExtension($extensionName);
            }
        }

        return $service;
    }

    protected function registerExtension(
        $extensionName,
        ProfileService $service,
        ServiceLocatorInterface $serviceLocator
    ) {
        $extension = $serviceLocator->get($extensionName);
        if ($extension instanceof AbstractExtension) {
            $service->registerExtension($extension);
        }
    }
}
