<?php
class Moxca_Util_ValidGreaterThanZeroInteger extends Zend_Validate_Abstract {
    function isValid($value) {
        $nameValidator = new Moxca_Util_ValidInteger();
        return ($nameValidator->isValid($value)) && ($value > 0);
    }
}
