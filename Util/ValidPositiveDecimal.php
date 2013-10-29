<?php
class Moxca_Util_ValidPositiveDecimal extends Zend_Validate_Abstract {
    function isValid($value) {
        $nameValidator = new Moxca_Util_ValidDecimal();
        return ($nameValidator->isValid($value)) && ($value >= 0.0);
    }
}
