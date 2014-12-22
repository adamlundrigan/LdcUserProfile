<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Extensions\ZfcUser;

use LdcUserProfile\Extensions\ZfcUser\ZfcUserForm;
use ZfcUser\Options\ModuleOptions;

class ZfcUserFormTest extends \PHPUnit_Framework_TestCase
{
    public function testCanInstantiate()
    {
        $form =  new ZfcUserForm(new ModuleOptions());

        $this->assertInstanceOf('ZfcUser\Form\Base', $form);
        $this->assertGreaterThan(0, count($form->getElements()));
    }
}
