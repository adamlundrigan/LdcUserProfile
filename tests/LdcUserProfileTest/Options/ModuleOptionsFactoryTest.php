<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Options;

class ModuleOptionsFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateServiceWithNoConfiguration()
    {
        $serviceManager = new \Zend\ServiceManager\ServiceManager();
        $serviceManager->setService('Config', array());

        $factory = new \LdcUserProfile\Options\ModuleOptionsFactory();
        $svc = $factory->createService($serviceManager);

        $this->assertInstanceOf('LdcUserProfile\Options\ModuleOptions', $svc);
    }

    public function testCreateServiceWithConfiguration()
    {
        $serviceManager = new \Zend\ServiceManager\ServiceManager();
        $serviceManager->setService('Config', array(
            'ldc-user-profile' => array(
                'url_path' => '/foo/bar'
            ),
        ));

        $factory = new \LdcUserProfile\Options\ModuleOptionsFactory();
        $svc = $factory->createService($serviceManager);

        $this->assertInstanceOf('LdcUserProfile\Options\ModuleOptions', $svc);
        $this->assertEquals('/foo/bar', $svc->getUrlPath());
    }
}
