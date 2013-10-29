<?php
class Moxca_View_Helper_FormatDateToShow {

    public function formatDateToShow($rawMySqlDate, $sep='/')
    {
        if (!is_null($rawMySqlDate)) {
            $dateArray = explode("-", $rawMySqlDate);
            $formatedDate = $dateArray[2] . $sep . $dateArray[1] . $sep . $dateArray[0];
            return $formatedDate;
        } else {
            return null;
        }
    }
}

