<?php
class Moxca_Util_ValidUri extends Zend_Validate_Abstract {
    function isValid($value) {
        if ($value == "") {
            return false;
        }
        $nameValidator = new Zend_Validate_Regex("/^[a-zA-Z0-9\/_|+ -]{0,200}$/");
        return $nameValidator->isValid($value);
    }
}
