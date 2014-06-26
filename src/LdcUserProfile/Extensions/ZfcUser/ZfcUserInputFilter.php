<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfile\Extensions\ZfcUser;

use ZfcUser\Form\RegisterFilter;
use ZfcUser\Options\RegistrationOptionsInterface;

class ZfcUserInputFilter extends RegisterFilter
{
    public function __construct($emailValidator, $usernameValidator, RegistrationOptionsInterface $options)
    {
        parent::__construct($emailValidator, $usernameValidator, $options);

        $this->get('password')->setRequired(false);
        $this->get('passwordVerify')->setRequired(false);
    }
}