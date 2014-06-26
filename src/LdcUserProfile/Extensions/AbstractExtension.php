<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfile\Extensions;

use ZfcUser\Entity\UserInterface;

abstract class AbstractExtension
{
    protected $fieldset;

    protected $inputFilter;

	public function getFieldset()
    {
        return $this->fieldset;
    }

	public function setFieldset($fieldset)
    {
        $this->fieldset = $fieldset;
        return $this;
    }

	public function getInputFilter()
    {
        return $this->inputFilter;
    }

	public function setInputFilter($inputFilter)
    {
        $this->inputFilter = $inputFilter;
        return $this;
    }

    /**
     * Persist changes to the extension entity
     *
     * @param \stdClass $entity
     * @return boolean
     */
    abstract public function save($entity);

    /**
     * Retrieve the extension entity associated with the current user
     *
     * @param UserInterface $user
     * @return \stdClass
     */
	abstract public function getObjectForUser(UserInterface $user);

	abstract public function getName();

}