<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Extensions\ZfcUser;

use LdcUserProfile\Extensions\ZfcUser\ZfcUserInputFilter;
class ZfcUserInputFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testInputFilterIsInstantiatedWithPasswordFieldsNotRequired()
    {
        $emailValidator     = \Mockery::mock('Zend\Validator\ValidatorInterface');
        $usernameValidator  = \Mockery::mock('Zend\Validator\ValidatorInterface');
        $options            = new \ZfcUser\Options\ModuleOptions();

        $inputFilter = new ZfcUserInputFilter($emailValidator, $usernameValidator, $options);

        $this->assertFalse($inputFilter->get('password')->isRequired());
        $this->assertFalse($inputFilter->get('passwordVerify')->isRequired());
    }
}
