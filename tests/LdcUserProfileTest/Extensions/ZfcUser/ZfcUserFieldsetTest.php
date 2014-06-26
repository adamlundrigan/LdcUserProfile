<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Extensions\ZfcUser;

use LdcUserProfile\Extensions\ZfcUser\ZfcUserFieldset;

class ZfcUserFieldsetTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider providerFieldWillAddTheStandardProfileFieldsFromBaseFormWhenTheyArePresent
     */
    public function testFieldWillAddTheStandardProfileFieldsFromBaseFormWhenTheyArePresent($setup)
    {
        $mockForm = \Mockery::mock('ZfcUser\Form\Base');

        $enabled = array();
        foreach ($setup as $field => $state) {
            $mockForm->shouldReceive('has')->withArgs(array($field))->once()->andReturn($state == 1);
            if ($state) {
                $element = \Mockery::mock('Zend\Form\ElementInterface');
                $element->shouldReceive('getName')->andReturn($field);
                $element->shouldReceive('setName')->withArgs(array($field == 'userId' ? 'id' : $field))->once();

                $mockForm->shouldReceive('get')->withArgs(array($field))->once()->andReturn($element);

                array_push($enabled, $field);
            }
        }

        $extension = new ZfcUserFieldset($mockForm);

        foreach ($enabled as $field) {
            $this->assertTrue($extension->has($field == 'userId' ? 'id' : $field));
        }
    }

    public function providerFieldWillAddTheStandardProfileFieldsFromBaseFormWhenTheyArePresent()
    {
        return array(
            array(array('userId' => 1, 'username' => 1, 'email' => 1, 'display_name' => 1, 'password' => 1, 'passwordVerify' => 1)),
            array(array('userId' => 0, 'username' => 1, 'email' => 1, 'display_name' => 1, 'password' => 1, 'passwordVerify' => 1)),
            array(array('userId' => 1, 'username' => 0, 'email' => 1, 'display_name' => 1, 'password' => 1, 'passwordVerify' => 1)),
            array(array('userId' => 1, 'username' => 1, 'email' => 0, 'display_name' => 1, 'password' => 1, 'passwordVerify' => 1)),
            array(array('userId' => 1, 'username' => 1, 'email' => 1, 'display_name' => 0, 'password' => 1, 'passwordVerify' => 1)),
            array(array('userId' => 1, 'username' => 1, 'email' => 1, 'display_name' => 1, 'password' => 0, 'passwordVerify' => 1)),
            array(array('userId' => 1, 'username' => 1, 'email' => 1, 'display_name' => 1, 'password' => 1, 'passwordVerify' => 0)),
        );
    }

}
