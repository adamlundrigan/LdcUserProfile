<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfile\Extensions\ZfcUser\Validator;

use ZfcUser\Validator\NoRecordExists;

class NoOtherRecordExists extends NoRecordExists
{
    public function isValid($value, $context = null)
    {
        $valid = true;
        $this->setValue($value);

        $result = $this->query($value);
        if ($result && $result->getId() != $context['id']) {
            $valid = false;
            $this->error(self::ERROR_RECORD_FOUND);
        }

        return $valid;
    }
}
