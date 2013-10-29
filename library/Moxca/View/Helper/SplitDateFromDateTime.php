<?php
class Moxca_View_Helper_SplitDateFromDateTime {

    public function splitDateFromDateTime($dateTime)
    {
        $splitDate = explode(" ", $dateTime);
        if (is_array($splitDate)) {
            return $splitDate[0];
        } else {
            return $dateTime;
        }
    }
}

