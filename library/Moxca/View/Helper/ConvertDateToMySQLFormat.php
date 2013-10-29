<?php

class Moxca_View_Helper_ConvertDateToMySQLFormat extends Zend_View_Helper_Abstract
{
    public function convertDateToMySQLFormat($date)
    {
        $dateValidator = new Moxca_Util_ValidDate();
        if($dateValidator->isValid($date)) {
                return implode("-", array_reverse(explode(!strstr($date, '/') ? "-" : "/", $date)));
        } else {
            throw new Moxca_Util_DateException('Invalid date!');
        }
    }
}

