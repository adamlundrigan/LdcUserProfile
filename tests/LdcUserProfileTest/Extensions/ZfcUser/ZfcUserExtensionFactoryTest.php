<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Extensions\ZfcUser;

class ZfcUserExtensionFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $mockUserService = \Mockery::mock('ZfcUser\Service\User');
        $mockFieldset = \Mockery::mock('Zend\Form\FieldsetInterface');
        $mockInputFilter = \Mockery::mock('Zend\InputFilter\InputFilterInterface');

        $serviceManager = new \Zend\ServiceManager\ServiceManager();
        $serviceManager->setService('zfcuser_user_service', $mockUserService);
        $serviceManager->setService('ldc-user-profile_extension_zfcuser_fieldset', $mockFieldset);
        $serviceManager->setService('ldc-user-profile_extension_zfcuser_inputfilter', $mockInputFilter);

        $factory = new \LdcUserProfile\Extensions\ZfcUser\ZfcUserExtensionFactory();
        $svc = $factory->createService($serviceManager);

        $this->assertInstanceOf('LdcUserProfile\Extensions\ZfcUser\ZfcUserExtension', $svc);
        $this->assertSame($mockUserService, $svc->getUserService());
        $this->assertSame($mockFieldset, $svc->getFieldset());
        $this->assertSame($mockInputFilter, $svc->getInputFilter());
    }
}
