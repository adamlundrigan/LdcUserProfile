<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Extensions\ZfcUser\Validator;

use LdcUserProfile\Extensions\ZfcUser\Validator\NoOtherRecordExists;
class NoOtherRecordExistsTest extends \PHPUnit_Framework_TestCase
{
    public function testWillRejectWhenMapperReturnsResultWithMismatchedId()
    {
        $mockUser = \Mockery::mock('ZfcUser\Entity\UserInterface');
        $mockUser->shouldReceive('getId')->andReturn(456);

        $mockMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockMapper->shouldReceive('findByUsername')->withArgs(array('foobar'))->once()->andReturn($mockUser);

        $validator = new NoOtherRecordExists(array(
            'mapper' => $mockMapper,
            'key'    => 'username'
        ));

        $this->assertFalse($validator->isValid('foobar', array('id' => 123)));
    }

    public function testWillAcceptWhenMapperReturnsResultWithSameId()
    {
        $mockUser = \Mockery::mock('ZfcUser\Entity\UserInterface');
        $mockUser->shouldReceive('getId')->andReturn(123);

        $mockMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockMapper->shouldReceive('findByUsername')->withArgs(array('foobar'))->once()->andReturn($mockUser);

        $validator = new NoOtherRecordExists(array(
            'mapper' => $mockMapper,
            'key'    => 'username'
        ));

        $this->assertTrue($validator->isValid('foobar', array('id' => 123)));
    }

    public function testWillAcceptWhenMapperReturnsNothing()
    {
        $mockMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockMapper->shouldReceive('findByUsername')->withArgs(array('foobar'))->once()->andReturn(null);

        $validator = new NoOtherRecordExists(array(
            'mapper' => $mockMapper,
            'key'    => 'username'
        ));

        $this->assertTrue($validator->isValid('foobar', array('id' => 123)));
    }
}
