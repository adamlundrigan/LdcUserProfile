<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfile\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ObjectProperty;

class PrototypeForm extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name ?: 'ldc-user-profile', $options);

        $this->setObject(new \stdClass());
        $this->setHydrator(new ObjectProperty());

        $submitElement = new \Zend\Form\Element\Button('submit');
        $submitElement->setLabel('Save Changes')->setAttributes(array('type'  => 'submit'));
        $this->add($submitElement, array('priority' => -1000));
    }
}
