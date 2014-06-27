<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ExtensionModule;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExtensionFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new Extension();
        $service->setFieldset(new Pieces\ExtensionFieldset());
        $service->setInputFilter(new Pieces\ExtensionInputFilter());
        
        $service->getFieldset()
                ->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty())
                ->setObject(new \stdClass());

        return $service;
    }
}
