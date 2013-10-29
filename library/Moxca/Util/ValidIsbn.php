<?php
class Moxca_Util_ValidIsbn extends Zend_Validate_Abstract {
    function isValid($value) {
        if ($value == "") {
            return false;
        }
        $validator = new Zend_Validate_Regex("/^(\d{9}(?:\d|X))$/");
        $isbn10Digit = $validator->isValid($value);
        $validator = new Zend_Validate_Regex("/^(\d{12}(?:\d|X))$/");
        $isbn13Digit = $validator->isValid($value);
        return $isbn10Digit || $isbn13Digit;
    }
}
