<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfile\Extensions\ZfcUser;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use LdcUserProfile\Extensions\ZfcUser\Validator\NoOtherRecordExists;

class ZfcUserInputFilterFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');
        $mapper  = $serviceLocator->get('zfcuser_user_mapper');

        $object = new ZfcUserInputFilter(
            new NoOtherRecordExists(array('mapper' => $mapper, 'key' => 'email')),
            new NoOtherRecordExists(array('mapper' => $mapper, 'key' => 'username')),
            $options
        );

        return $object;
    }
}
