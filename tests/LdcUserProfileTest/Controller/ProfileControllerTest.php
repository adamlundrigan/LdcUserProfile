<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Controller;

use LdcUserProfile\Controller\ProfileController;

class ProfileControllerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->controller = new ProfileController();
    }

    public function testGetSetService()
    {
        $mockService = \Mockery::mock('LdcUserProfile\Service\ProfileService');
        
        $this->controller->setService($mockService);
        $this->assertSame($mockService, $this->controller->getService());
    }
    
    public function testGetServicePullsFromServiceLocatorWhenNotDefined()
    {
        $mockService = \Mockery::mock('LdcUserProfile\Service\ProfileService');
        
        $serviceLocator = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocator->shouldReceive('get')->once()->andReturn($mockService);
        
        $this->controller->setServiceLocator($serviceLocator);
        $this->assertSame($mockService, $this->controller->getService());
    }
    
    public function testGetSetModuleOptions()
    {
        $mockOptions = \Mockery::mock('LdcUserProfile\Options\ModuleOptions');
        
        $this->controller->setModuleOptions($mockOptions);
        $this->assertSame($mockOptions, $this->controller->getModuleOptions());
    }
        
    public function testGetModuleOptionsPullsFromServiceLocatorWhenNotDefined()
    {
        $mockOptions = \Mockery::mock('LdcUserProfile\Options\ModuleOptions');

        $serviceLocator = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocator->shouldReceive('get')->once()->andReturn($mockOptions);
        
        $this->controller->setServiceLocator($serviceLocator);
        $this->assertSame($mockOptions, $this->controller->getModuleOptions());
    }
}
