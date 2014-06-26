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
}