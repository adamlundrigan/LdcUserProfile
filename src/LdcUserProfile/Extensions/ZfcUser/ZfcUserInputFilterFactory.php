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

class ZfcUserInputFilterFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');

        $object = new ZfcUserInputFilter(
            array('name' => 'EmailAddress'),  //todo
            array('name' => 'Alnum'),  //todo
            $options
        );

        return $object;
    }
}