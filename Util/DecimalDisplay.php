<?php
class Moxca_Util_DecimalDisplay {



    public function FormatDecimal($rawValue)
    {
        if ($rawValue !== null) {
            $decimal = sprintf('%.2f', $rawValue);;
        } else {
            $decimal = "";
        }


        return $decimal;
    }
}

