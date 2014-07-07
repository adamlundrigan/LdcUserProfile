<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfile\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    /**
     * On/Off switch for profile feature
     */
    protected $isEnabled = true;

    /**
     * URL path at which to mount the profile controller
     */
    protected $urlPath = '/user/profile';

    /**
     * Override of default validation groups to switch editing on/off for specific fields
     *
     * @var array
     */
    protected $validationGroupOverrides = array();

    /**
     * Registered extensions
     *
     * @var array
     */
    protected $registeredExtensions = array();

    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled == true;

        return $this;
    }

    public function isEnabled()
    {
        return $this->getIsEnabled() == true;
    }

    public function getUrlPath()
    {
        return $this->urlPath;
    }

    public function setUrlPath($urlPath)
    {
        $this->urlPath = $urlPath;

        return $this;
    }

    public function getValidationGroupOverrides()
    {
        return $this->validationGroupOverrides;
    }

    public function setValidationGroupOverrides(array $vg)
    {
        $this->validationGroupOverrides = $vg;

        return $this;
    }

    public function getRegisteredExtensions()
    {
        return $this->registeredExtensions;
    }

    public function setRegisteredExtensions(array $ext)
    {
        $this->registeredExtensions = $ext;

        return $this;
    }

}
