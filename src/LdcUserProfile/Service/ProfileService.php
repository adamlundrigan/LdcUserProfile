<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfile\Service;

use Zend\Form\FormInterface;
use Zend\EventManager\EventManagerInterface;
use LdcUserProfile\Extensions\AbstractExtension;
use ZfcUser\Entity\UserInterface;
use LdcUserProfile\Form\PrototypeForm;
use LdcUserProfile\Options\ModuleOptions;

class ProfileService
{
    /**
     * @var array<AbstractExtension>
     */
    protected $extensions = array();

    /**
     * @var FormInterface
     */
    protected $formPrototype;

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * @var mixed
     */
    protected $eventIdentifier;

    public function registerExtension(AbstractExtension $e)
    {
        $argv = array('extension' => $e);

        $this->getEventManager()->trigger(__METHOD__ . '.pre', $this, $argv);
        $this->extensions[$e->getName()] = $e;
        $this->getEventManager()->trigger(__METHOD__ . '.post', $this, $argv);

        return $this;
    }

    public function unregisterExtension($nameOrInstance)
    {
        $argv = array('extension' => $nameOrInstance);

        $this->getEventManager()->trigger(__METHOD__ . '.pre', $this, $argv);
        unset($this->extensions[
            $nameOrInstance instanceof AbstractExtension
                ? $nameOrInstance->getName()
                : (string) $nameOrInstance]
        );
        $this->getEventManager()->trigger(__METHOD__ . '.post', $this, $argv);

        return $this;
    }

    public function getExtensions()
    {
        return $this->extensions;
    }

    public function hasExtension($nameOrInstance)
    {
        return array_key_Exists(
            $nameOrInstance instanceof AbstractExtension
                ? $nameOrInstance->getName()
                : (string) $nameOrInstance,
            $this->extensions
        );
    }

    public function constructFormForUser(UserInterface $user)
    {
        $form = clone $this->getFormPrototype();
        $entity = clone $form->getObject();
        $argv = compact('form', 'entity', 'user');

        $vgOverrides = $this->getModuleOptions()->getValidationGroupOverrides();

        $this->getEventManager()->trigger(__METHOD__ . '.pre', $this, $argv);

        $validationGroup = array();
        foreach ( $this->getExtensions() as $name => $ext ) {
            $form->add(clone $ext->getFieldset(), array('name' => $name));
            $form->getInputFilter()->add(clone $ext->getInputFilter(), $name);
            $entity->{$name} = $ext->getObjectForUser($user);

            $this->getEventManager()->trigger(__METHOD__ . '.extension', $this, $argv + array(
                'extension' => $ext,
            ));

            // Process validation group + overrides
            if ( isset($vgOverrides[$name]) ) {
                $ext->setFieldsetValidationGroup($vgOverrides[$name]);
            }
            $validationGroup[$name] = $ext->getFieldsetValidationGroup();
            if (empty($validationGroup[$name])) {
                $validationGroup[$name] = array();
            }
        }
        $form->setValidationGroup($validationGroup);

        $form->bind($entity);

        unset($argv['extension']);
        $this->getEventManager()->trigger(__METHOD__ . '.post', $this, $argv);

        return $form;
    }

    public function save($entity)
    {
        $argv = compact('entity');

        $this->getEventManager()->trigger(__METHOD__ . '.pre', $this, $argv);

        $result = true;
        foreach ( $this->getExtensions() as $name => $ext ) {
            if ( ! $ext->save($entity) ) {
                $result = false;
            }
        }

        unset($argv['extension']);
        $this->getEventManager()->trigger(__METHOD__ . '.post', $this, $argv + array(
            'result' => $result
        ));

        return $result;
    }

    public function getFormPrototype()
    {
        if ( is_null($this->formPrototype) ) {
            $this->formPrototype = new PrototypeForm();
        }

        return $this->formPrototype;
    }

    public function setFormPrototype(FormInterface $form)
    {
        $this->formPrototype = $form;

        return $this;
    }

    public function getModuleOptions()
    {
        if (! $this->moduleOptions instanceof ModuleOptions) {
            $this->moduleOptions = new ModuleOptions();
        }

        return $this->moduleOptions;
    }

    public function setModuleOptions(ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;

        return $this;
    }

    /**
     * Set the event manager instance used by this context.
     *
     * For convenience, this method will also set the class name / LSB name as
     * identifiers, in addition to any string or array of strings set to the
     * $this->eventIdentifier property.
     *
     * @param  EventManagerInterface $events
     * @return mixed
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $identifiers = array(__CLASS__, get_class($this));
        if (isset($this->eventIdentifier)) {
            if ((is_string($this->eventIdentifier))
                || (is_array($this->eventIdentifier))
                || ($this->eventIdentifier instanceof \Traversable)
            ) {
                $identifiers = array_unique(array_merge($identifiers, (array) $this->eventIdentifier));
            } elseif (is_object($this->eventIdentifier)) {
                $identifiers[] = $this->eventIdentifier;
            }
            // silently ignore invalid eventIdentifier types
        }
        $events->setIdentifiers($identifiers);
        $this->events = $events;

        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->events instanceof EventManagerInterface) {
            $this->setEventManager(new \Zend\EventManager\EventManager());
        }

        return $this->events;
    }
}
