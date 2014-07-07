<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Options;

use LdcUserProfile\Options\ModuleOptions as Options;

class ModuleOptionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Options $options
     */
    protected $options;

    public function setUp()
    {
        $this->options = new Options();
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf('LdcUserProfile\Options\ModuleOptions', $this->options);
    }

    public function testGetSetUrlPath()
    {
        $this->options->setUrlPath('/foo/bar');
        $this->assertEquals('/foo/bar', $this->options->getUrlPath());
    }

    public function testGetSetIsEnabled()
    {
        $this->options->setIsEnabled(false);
        $this->assertEquals(false, $this->options->getIsEnabled());
        $this->assertEquals(false, $this->options->isEnabled());
    }

    public function testGetSetValidationGroupOverrides()
    {
        $data = array('foobar' => array('bazbat'));
        $this->options->setValidationGroupOverrides($data);
        $this->assertEquals($data, $this->options->getValidationGroupOverrides());
    }

    public function testSetValidationGroupOverridesRequiresArray()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');
        $this->options->setValidationGroupOverrides('foo');
    }

    public function testGetSetRegisteredExtensions()
    {
        $data = array('foo', 'bar');
        $this->options->setRegisteredExtensions($data);
        $this->assertEquals($data, $this->options->getRegisteredExtensions());
    }

    public function testSetRegisteredExtensionsRequiresArray()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');
        $this->options->setRegisteredExtensions('foo');
    }
}
