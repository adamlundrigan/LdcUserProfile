<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Extensions\ZfcUser;

class ZfcUserFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $mockOptions = new \ZfcUser\Options\ModuleOptions();
        $mockOptions->setUserEntityClass('ArrayObject');
        $mockHydrator = \Mockery::mock('Zend\Stdlib\Hydrator\HydratorInterface');

        $serviceManager = new \Zend\ServiceManager\ServiceManager();
        $serviceManager->setService('zfcuser_module_options', $mockOptions);
        $serviceManager->setService('zfcuser_user_hydrator', $mockHydrator);

        $factory = new \LdcUserProfile\Extensions\ZfcUser\ZfcUserFieldsetFactory();
        $svc = $factory->createService($serviceManager);

        $this->assertInstanceOf('LdcUserProfile\Extensions\ZfcUser\ZfcUserFieldset', $svc);
        $this->assertSame($mockHydrator, $svc->getHydrator());
        $this->assertInstanceOf('ArrayObject', $svc->getObject());
    }
}
