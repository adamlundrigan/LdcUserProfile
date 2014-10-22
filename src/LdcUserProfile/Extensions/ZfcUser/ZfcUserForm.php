<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfile\Extensions\ZfcUser;

use ZfcUser\Form\Register;
use ZfcUser\Options\RegistrationOptionsInterface;

class ZfcUserForm extends Register
{
    protected $registrationOptions;

    public function __construct(RegistrationOptionsInterface $registrationOptions)
    {
        $this->setRegistrationOptions($registrationOptions);
        parent::__construct(null, $registrationOptions);
    }

    /**
     * Set Registration Options
     *
     * @param  RegistrationOptionsInterface $registrationOptions
     * @return ZfcUserForm
     */
    public function setRegistrationOptions(RegistrationOptionsInterface $registrationOptions)
    {
        $this->registrationOptions = $registrationOptions;

        return $this;
    }

    /**
     * Get Registration Options
     *
     * @return RegistrationOptionsInterface
     */
    public function getRegistrationOptions()
    {
        return $this->registrationOptions;
    }
}
