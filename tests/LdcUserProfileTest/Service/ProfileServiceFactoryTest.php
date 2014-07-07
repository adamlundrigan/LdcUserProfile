<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Service;

use LdcUserProfile\Options\ModuleOptions;

class ProfileServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $mockModuleOptions = \Mockery::mock('LdcUserProfile\Options\ModuleOptions');
        $mockModuleOptions->shouldReceive('getRegisteredExtensions')->andReturn(array());

        $serviceManager = new \Zend\ServiceManager\ServiceManager();
        $serviceManager->setService('ldc-user-profile_module_options', $mockModuleOptions);

        $factory = new \LdcUserProfile\Service\ProfileServiceFactory();
        $svc = $factory->createService($serviceManager);

        $this->assertInstanceOf('LdcUserProfile\Service\ProfileService', $svc);
        $this->assertSame($mockModuleOptions, $svc->getModuleOptions());
    }

    public function testCreateServiceWithRegisteredExtension()
    {
        $moduleOptions = new ModuleOptions();
        $moduleOptions->setRegisteredExtensions(array('foo' => true));

        $mockExtension = \Mockery::mock('LdcUserProfile\Extensions\AbstractExtension');
        $mockExtension->shouldReceive('getName')->andReturn('foo');

        $serviceManager = new \Zend\ServiceManager\ServiceManager();
        $serviceManager->setService('ldc-user-profile_module_options', $moduleOptions);
        $serviceManager->setService('foo', $mockExtension);

        $factory = new \LdcUserProfile\Service\ProfileServiceFactory();
        $svc = $factory->createService($serviceManager);

        $this->assertInstanceOf('LdcUserProfile\Service\ProfileService', $svc);
        $this->assertTrue($svc->hasExtension('foo'));
        $this->assertTrue($svc->hasExtension($mockExtension));

        $extensions = $svc->getExtensions();
        $this->assertSame($mockExtension, $extensions['foo']);
    }

    public function testCreateServiceWithUnregisterExtension()
    {
        $moduleOptions = new ModuleOptions();
        $moduleOptions->setRegisteredExtensions(array('foo' => false));

        $mockExtension = \Mockery::mock('LdcUserProfile\Extensions\AbstractExtension');
        $mockExtension->shouldReceive('getName')->andReturn('foo');

        $serviceManager = new \Zend\ServiceManager\ServiceManager();
        $serviceManager->setService('ldc-user-profile_module_options', $moduleOptions);
        //$serviceManager->setService('foo', $mockExtension);

        $factory = new \LdcUserProfile\Service\ProfileServiceFactory();
        $svc = $factory->createService($serviceManager);

        $this->assertInstanceOf('LdcUserProfile\Service\ProfileService', $svc);
        $this->assertFalse($svc->hasExtension('foo'));
        $this->assertFalse($svc->hasExtension($mockExtension));
    }
}
