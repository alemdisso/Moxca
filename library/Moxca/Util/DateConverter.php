<?php

class Moxca_Util_DateConverter
{
    public function convertDateToMySQLFormat($date)
    {
            class_exists('Moxca_Util_ValidDate') || require "validDate.php";
            $dateValidator = new Moxca_Util_ValidDate();
            if($dateValidator->isValid($date)) {
                    return implode("-", array_reverse(explode(!strstr($date, '/') ? "-" : "/", $date)));
            } else {
               throw new Moxca_Util_DateException('Invalid date!');
            }
    }
}

?>
