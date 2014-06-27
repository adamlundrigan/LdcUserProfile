<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Extensions\ZfcUser;

class ZfcUserInputFilterFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $mockOptions = new \ZfcUser\Options\ModuleOptions();
        $mockMapper  = \Mockery::mock('ZfcUser\Mapper\UserInterface');

        $serviceManager = new \Zend\ServiceManager\ServiceManager();
        $serviceManager->setService('zfcuser_module_options', $mockOptions);
        $serviceManager->setService('zfcuser_user_mapper', $mockMapper);

        $factory = new \LdcUserProfile\Extensions\ZfcUser\ZfcUserInputFilterFactory();
        $svc = $factory->createService($serviceManager);

        $this->assertInstanceOf('LdcUserProfile\Extensions\ZfcUser\ZfcUserInputFilter', $svc);
    }
}
