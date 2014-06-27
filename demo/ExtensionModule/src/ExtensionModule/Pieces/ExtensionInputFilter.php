<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ExtensionModule\Pieces;

use Zend\InputFilter\InputFilter;

class ExtensionInputFilter extends InputFilter
{
    public function __construct()
    {        
        $this->add(array(
            'name'       => 'twitter',
            'required'   => true,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(array('name' => 'Alnum')),
        ));
        
        $this->add(array(
            'name'       => 'github',
            'required'   => true,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(array('name' => 'Alnum')),
        ));
        
        $this->add(array(
            'name'       => 'homepage',
            'required'   => false,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(array('name' => 'Uri')),
        ));
    }
}