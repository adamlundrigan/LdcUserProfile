<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Extensions;

abstract class AbstractExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testExtensionExtendsAbstract()
    {
        $this->assertInstanceOf('LdcUserProfile\Extensions\AbstractExtension', $this->extension);
    }

    public function testGetSetFieldset()
    {
        $mock = \Mockery::mock('Zend\Form\FieldsetInterface');
        $this->extension->setFieldset($mock);
        $this->assertSame($mock, $this->extension->getFieldset());
    }

    public function testGetSetInputFilter()
    {
        $mock = \Mockery::mock('Zend\InputFilter\InputFilterInterface');
        $this->extension->setInputFilter($mock);
        $this->assertSame($mock, $this->extension->getInputFilter());
    }
}