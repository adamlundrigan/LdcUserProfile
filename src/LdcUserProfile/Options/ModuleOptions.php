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
     * URL path at which to mount the profile controller
     */
    protected $urlPath = '/user/profile';
    
    
    public function getUrlPath() 
    {
        return $this->urlPath;
    }

    public function setUrlPath($urlPath) 
    {
        $this->urlPath = $urlPath;
        return $this;
    }
    
}
