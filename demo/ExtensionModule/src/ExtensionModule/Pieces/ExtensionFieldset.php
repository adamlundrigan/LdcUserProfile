<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ExtensionModule\Pieces;

use Zend\Form\Fieldset;

class ExtensionFieldset extends Fieldset
{
    public function __construct()
    {
        parent::__construct('modext');
        
        $this->add(array(
            'name' => 'twitter',
            'type' => 'Text',
            'options' => array(
                'label' => 'Twitter'
            )
        ));
        
        $this->add(array(
            'name' => 'github',
            'type' => 'Text',
            'options' => array(
                'label' => 'GitHub'
            )
        ));
        
        $this->add(array(
            'name' => 'homepage',
            'type' => 'Url',
            'options' => array(
                'label' => 'Homepage'
            )
        ));
    }

}