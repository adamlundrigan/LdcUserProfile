<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Service;

use LdcUserProfile\Service\ProfileService;
use Zend\Form\Element\Text;
use Zend\Stdlib\Hydrator\ObjectProperty;

class ProfileServiceTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->service = new ProfileService();
    }

    public function testGetSetFormPrototype()
    {
        $mock = \Mockery::mock('Zend\Form\FormInterface');

        $this->service->setFormPrototype($mock);
        $this->assertSame($mock, $this->service->getFormPrototype());
    }

    public function testRegisterExtension()
    {
        $ext = \Mockery::mock('LdcUserProfile\Extensions\AbstractExtension');
        $ext->shouldReceive('getName')->andReturn('testext');

        $this->service->registerExtension($ext);
        $this->assertArrayHasKey('testext', $this->service->getExtensions());

        return $ext;
    }

    public function testRegisterExtensionRejectsInvalidExtension()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');
        $this->service->registerExtension(new \stdClass());
    }

    public function testUnregisterExtensionByInstance()
    {
        $ext = $this->testRegisterExtension();

        $this->service->unregisterExtension($ext);
        $this->assertArrayNotHasKey('testext', $this->service->getExtensions());
    }

    public function testUnregisterExtensionByName()
    {
        $ext = $this->testRegisterExtension();

        $this->service->unregisterExtension($ext->getName());
        $this->assertArrayNotHasKey('testext', $this->service->getExtensions());
    }

    public function testSaveCallsSaveOnEachRegsiteredExtension()
    {
        $payload = new \stdClass();

        $mock = $this->testRegisterExtension();
        $mock->shouldReceive('save')->once()->withArgs(array($payload))->andReturn(true);

        $this->assertTrue($this->service->save($payload));
    }

    public function testSaveCallsSaveOnEachRegsiteredExtensionAndReturnsFalseWhenAnExtensionFails()
    {
        $payload = new \stdClass();

        $mock = $this->testRegisterExtension();
        $mock->shouldReceive('save')->once()->withArgs(array($payload))->andReturn(false);

        $this->assertFalse($this->service->save($payload));
    }

    public function testConstructFormForUser()
    {
        $mockUserData = new \stdClass();
        $mockUserData->test = 'hi';

        $mockFieldset = \Mockery::mock('Zend\Form\Fieldset[getName,setName]');
        $mockFieldset->shouldReceive('getName')->andReturn('testext');
        $mockFieldset->shouldReceive('setName')->withArgs(array('testext'))->once();
        $mockFieldset->add(new Text('test'));
        $mockFieldset->setHydrator(new ObjectProperty());
        $mockFieldset->setObject(new \stdClass());

        $mockInputFilter = new \Zend\InputFilter\InputFilter();
        $mockInputFilter->add(new \Zend\InputFilter\Input('test'));

        $mockUser = \Mockery::mock('ZfcUser\Entity\UserInterface');

        $ext = $this->testRegisterExtension();
        $ext->shouldReceive('getFieldset')->once()->andReturn($mockFieldset);
        $ext->shouldReceive('getInputFilter')->once()->andReturn($mockInputFilter);
        $ext->shouldReceive('getObjectForUser')->withArgs(array($mockUser))->once()->andReturn($mockUserData);

        $form = $this->service->constructFormForUser($mockUser);

        $this->assertInstanceOf('Zend\Form\FormInterface', $form);
        $this->assertTrue($form->has('testext'));
        $this->assertTrue($form->get('testext')->has('test'));
        $this->assertEquals('hi', $form->get('testext')->get('test')->getValue());
        $this->assertTrue($form->getInputFilter()->has('testext'));
        $this->assertTrue($form->getInputFilter()->get('testext')->has('test'));
    }
}