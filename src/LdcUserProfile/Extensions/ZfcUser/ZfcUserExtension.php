<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfile\Extensions\ZfcUser;

use LdcUserProfile\Extensions\AbstractExtension;
use ZfcUser\Entity\UserInterface;
use ZfcUser\Service\User as UserService;
use Zend\Crypt\Password\Bcrypt;

class ZfcUserExtension extends AbstractExtension
{
    /**
     * @var UserService
     */
    protected $userService;

    public function getName()
    {
        return 'zfcuser';
    }

    /**
     * Defines the validation group for the provided fieldset
     *
     * @return array
     */
    public function getFieldsetValidationGroup()
    {
        $parentvg = parent::getFieldsetValidationGroup();
        if ( empty($parentvg) ) {
            $parentvg = array();
            foreach ( $this->getFieldset()->getElements() as $element ) {
                array_push($parentvg, $element->getName());
            }
            $this->setFieldsetValidationGroup($parentvg);
        }

        return $parentvg;
    }

    /**
     * Retrieve the extension entity associated with the current user
     * (In this case it's a dummy method as the user is the entity)
     *
     * @param  UserInterface $user
     * @return UserInterface
     */
    public function getObjectForUser(UserInterface $user)
    {
        $object = clone $user;
        $object->setPassword('');

        return $object;
    }

    public function save($entity)
    {
        if ( ! isset($entity->zfcuser) || ! $entity->zfcuser instanceof UserInterface ) {
            throw new \RuntimeException('Entity must implement ZfcUser\Entity\UserInterface');
        }

        // If the user specified a new password, hash it
        $password = $entity->zfcuser->getPassword();
        if ( ! empty($password) ) {
            $hydrator = $this->getFieldset()->getHydrator();
            if ( method_exists($hydrator, 'getCryptoService') ) {
                // ZfcUser dev-master
                $hash = $this->getFieldset()->getHydrator()->getCryptoService()->create($password);
            } else {
                $bcrypt = new Bcrypt();
                $bcrypt->setCost($this->getUserService()->getOptions()->getPasswordCost());
                $hash = $bcrypt->create($password);
            }
            $entity->zfcuser->setPassword($hash);

            // Clear out the password values now that we don't need them again
            $this->getFieldset()->get('password')->setValue('');
            $this->getFieldset()->get('passwordVerify')->setValue('');
        }

        // Reload the actual user entity and transfer changes to it
        // (necessary for ZfcUserDoctrineORM to work, as $entity->zfcuser is disconnected)
        $userobj = $this->getUserService()->getUserMapper()->findById($entity->zfcuser->getId());
        $this->transferChangesToExistingEntity($entity->zfcuser, $userobj);

        // Stash the new entity back in the original's place so that later
        // extensions can use it in Doctrine associations safely
        $entity->zfcuser = $userobj;

        return $this->getUserService()->getUserMapper()->update($userobj);
    }

    public function transferChangesToExistingEntity(UserInterface $newEntity, UserInterface $existingEntity)
    {
        $existingEntity->setUsername($newEntity->getUsername());
        $existingEntity->setEmail($newEntity->getEmail());
        $existingEntity->setDisplayName($newEntity->getDisplayName());
        $existingEntity->setState($newEntity->getState());

        $passwordHash = $newEntity->getPassword();
        if ( ! empty($passwordHash) ) {
            $existingEntity->setPassword($passwordHash);
        }
    }

    public function setUserService(UserService $svc)
    {
        $this->userService = $svc;

        return $this;
    }

    public function getUserService()
    {
        return $this->userService;
    }
}
