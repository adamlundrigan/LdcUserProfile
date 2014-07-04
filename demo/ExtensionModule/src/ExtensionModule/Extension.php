<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ExtensionModule;

use LdcUserProfile\Extensions\AbstractExtension;

class Extension extends AbstractExtension
{

    protected $session;
    
    public function getName() 
    {
        return 'modext';
    }

    public function getObjectForUser(\ZfcUser\Entity\UserInterface $user) 
    {
        if ( ! $this->getSession()->offsetExists("U{$user->getId()}") ) {
            return new \stdClass();
        }
        return $this->getSession()->offsetGet("U{$user->getId()}");
    }

    public function save($entity) 
    {
        if ( !isset($entity->modext) ) {
            return false;
        }
        $this->getSession()->offsetSet("U{$entity->zfcuser->getId()}", $entity->modext);
        return true;
    }
    
    public function getSession()
    {
        if ( is_null($this->session) ) {
            $this->setSession(new \Zend\Session\Container('ExtensionModule'));
        }
        return $this->session;
    }
    
    public function setSession(\Zend\Session\Container $c)
    {
        $this->session = $c;
        return $this;
    }

}