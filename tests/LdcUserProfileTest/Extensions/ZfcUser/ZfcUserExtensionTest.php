<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Extensions\ZfcUser;

use LdcUserProfileTest\Extensions\AbstractExtensionTest;
use LdcUserProfile\Extensions\ZfcUser\ZfcUserExtension;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZfcUserExtensionTest extends AbstractExtensionTest
{
    public function setUp()
    {
        $this->extension = new ZfcUserExtension();
    }

    public function testGetSetUserService()
    {
        $mockUserService = \Mockery::mock('ZfcUser\Service\User');
        $this->extension->setUserService($mockUserService);
        $this->assertSame($mockUserService, $this->extension->getUserService());
    }

    public function testGetObjectForUserReturnsUserInstanceWithoutPasswordHashValue()
    {
        $user = \Mockery::mock('ZfcUser\Entity\UserInterface');
        $user->shouldReceive('setPassword')->once()->withArgs(array(''));

        $result = $this->extension->getObjectForUser($user);
        $this->assertNotSame($result, $user);
        $this->assertInstanceOf('ZfcUser\Entity\UserInterface', $user);
    }

    public function testSaveRejectsImproperlyConstructedArgument()
    {
        $arg = new \stdClass();
        $arg->foobar = 'bazbat';

        $this->setExpectedException('RuntimeException');
        $this->extension->save($arg);
    }

    public function testSaveProxiesToUserMapper()
    {
        // Stub out transferChangesToExistingEntity because we don't care about it here
        $this->extension = \Mockery::mock('LdcUserProfile\Extensions\ZfcUser\ZfcUserExtension[transferChangesToExistingEntity]');
        $this->extension->shouldReceive('transferChangesToExistingEntity')->once();

        $arg = new \stdClass();
        $arg->zfcuser = \Mockery::mock('ZfcUser\Entity\UserInterface');
        $arg->zfcuser->shouldReceive('getId')->andReturn(42);
        $arg->zfcuser->shouldReceive('getPassword')->andReturn('');

        $mockUserEntity = \Mockery::mock('ZfcUser\Entity\UserInterface');

        $mockUserMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockUserMapper->shouldReceive('findById')->once()->withArgs(array(42))->andReturn($mockUserEntity);
        $mockUserMapper->shouldReceive('update')->withArgs(array($mockUserEntity))->once()->andReturn('boolean');
        $mockUserService = \Mockery::mock('ZfcUser\Service\User');
        $mockUserService->shouldReceive('getUserMapper')->andReturn($mockUserMapper);
        $this->extension->setUserService($mockUserService);

        $this->assertEquals('boolean', $this->extension->save($arg));
        $this->assertSame($mockUserEntity, $arg->zfcuser);
    }

    public function testSaveWillHashTheUsersPasswordInZfcUserOneOneZeroAndBelow()
    {
        // Stub out transferChangesToExistingEntity because we don't care about it here
        $this->extension = \Mockery::mock('LdcUserProfile\Extensions\ZfcUser\ZfcUserExtension[transferChangesToExistingEntity]');
        $this->extension->shouldReceive('transferChangesToExistingEntity')->once();

        $arg = new \stdClass();
        $arg->zfcuser = \Mockery::mock('ZfcUser\Entity\UserInterface');
        $arg->zfcuser->shouldReceive('getId')->andReturn(42);
        $arg->zfcuser->shouldReceive('getPassword')->andReturn('bazbat');
        $arg->zfcuser->shouldReceive('setPassword')->once()->with(\Mockery::on(function ($arg) {
            return preg_match('{^[$][a-z0-9]{2}[$]04[$]}is', $arg) !== false;
        }));

        $mockHydrator = \Mockery::mock('Zend\Stdlib\Hydrator\HydratorInterface');
        $mockFieldset = \Mockery::mock('Zend\Form\FieldsetInterface');
        $mockFieldset->shouldReceive('getHydrator')->once()->andReturn($mockHydrator);
        $mockFieldset->shouldReceive('get->setValue');
        $this->extension->setFieldset($mockFieldset);

        $mockUserEntity = \Mockery::mock('ZfcUser\Entity\UserInterface');
        $mockUserMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockUserMapper->shouldReceive('findById')->once()->withArgs(array(42))->andReturn($mockUserEntity);
        $mockUserMapper->shouldReceive('update')->withArgs(array($mockUserEntity))->once()->andReturn('boolean');
        $mockUserService = \Mockery::mock('ZfcUser\Service\User');
        $mockUserService->shouldReceive('getUserMapper')->andReturn($mockUserMapper);
        $mockUserService->shouldReceive('getOptions->getPasswordCost')->once()->andReturn(4);
        $this->extension->setUserService($mockUserService);

        $this->assertEquals('boolean', $this->extension->save($arg));
    }

    public function testSaveWillHashTheUsersPasswordInZfcUserDevMaster()
    {
        // Stub out transferChangesToExistingEntity because we don't care about it here
        $this->extension = \Mockery::mock('LdcUserProfile\Extensions\ZfcUser\ZfcUserExtension[transferChangesToExistingEntity]');
        $this->extension->shouldReceive('transferChangesToExistingEntity')->once();

        $arg = new \stdClass();
        $arg->zfcuser = \Mockery::mock('ZfcUser\Entity\UserInterface');
        $arg->zfcuser->shouldReceive('getId')->andReturn(42);
        $arg->zfcuser->shouldReceive('getPassword')->andReturn('bazbat');
        $arg->zfcuser->shouldReceive('setPassword')->once()->with(\Mockery::on(function ($arg) {
            return preg_match('{^[$][a-z0-9]{2}[$]04[$]}is', $arg) !== false;
        }));

        $mockHydrator = \Mockery::mock('LdcUserProfileTest\Extensions\ZfcUser\HydratorWithCrypto');
        $mockHydrator->shouldReceive('getCryptoService->create')->withArgs(array('bazbat'));

        $mockFieldset = \Mockery::mock('Zend\Form\FieldsetInterface');
        $mockFieldset->shouldReceive('getHydrator')->andReturn($mockHydrator);
        $mockFieldset->shouldReceive('get->setValue');
        $this->extension->setFieldset($mockFieldset);

        $mockUserEntity = \Mockery::mock('ZfcUser\Entity\UserInterface');
        $mockUserMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockUserMapper->shouldReceive('findById')->once()->withArgs(array(42))->andReturn($mockUserEntity);
        $mockUserMapper->shouldReceive('update')->withArgs(array($mockUserEntity))->once()->andReturn('boolean');
        $mockUserService = \Mockery::mock('ZfcUser\Service\User');
        $mockUserService->shouldReceive('getUserMapper')->andReturn($mockUserMapper);
        $this->extension->setUserService($mockUserService);

        $this->assertEquals('boolean', $this->extension->save($arg));
    }

    public function testTransferChangesToExistingEntity()
    {
        $src = \Mockery::mock('ZfcUser\Entity\UserInterface');
        $src->shouldReceive('getUsername')->andReturn('test');
        $src->shouldReceive('getEmail')->andReturn('test@test.com');
        $src->shouldReceive('getDisplayName')->andReturn('Test Guy');
        $src->shouldReceive('getState')->andReturn(1);
        $src->shouldReceive('getPassword')->andReturn('fizzbuzz');

        $dest = \Mockery::mock('ZfcUser\Entity\UserInterface');
        $dest->shouldReceive('setUsername')->withArgs(array('test'));
        $dest->shouldReceive('setEmail')->withArgs(array('test@test.com'));
        $dest->shouldReceive('setDisplayName')->withArgs(array('Test Guy'));
        $dest->shouldReceive('setState')->withArgs(array('1'));
        $dest->shouldReceive('setPassword')->withArgs(array('fizzbuzz'));

        $this->extension->transferChangesToExistingEntity($src, $dest);
    }

    public function testTransferChangesToExistingEntityWillNotOverwritePasswordWithEmptyString()
    {
        $src = \Mockery::mock('ZfcUser\Entity\UserInterface');
        $src->shouldReceive('getUsername')->andReturn('test');
        $src->shouldReceive('getEmail')->andReturn('test@test.com');
        $src->shouldReceive('getDisplayName')->andReturn('Test Guy');
        $src->shouldReceive('getState')->andReturn(1);
        $src->shouldReceive('getPassword')->andReturn('');

        $dest = \Mockery::mock('ZfcUser\Entity\UserInterface');
        $dest->shouldReceive('setUsername')->withArgs(array('test'));
        $dest->shouldReceive('setEmail')->withArgs(array('test@test.com'));
        $dest->shouldReceive('setDisplayName')->withArgs(array('Test Guy'));
        $dest->shouldReceive('setState')->withArgs(array('1'));
        $dest->shouldReceive('setPassword')->never();

        $this->extension->transferChangesToExistingEntity($src, $dest);
    }

    public function testGetFieldsetValidationGroupWillAutogenerateListFromFieldsetWhenEmpty()
    {
        $fieldset = new \Zend\Form\Fieldset();
        $fieldset->add(array('name' => 'test', 'type' => 'Text'));

        $this->extension->setFieldset($fieldset);
        $this->assertEquals(array('test'), $this->extension->getFieldsetValidationGroup());
    }
}

class HydratorWithCrypto implements HydratorInterface
{
    public function extract($object) {}
    public function hydrate(array $data, $object) {}
    public function getCryptoService() {}
}
