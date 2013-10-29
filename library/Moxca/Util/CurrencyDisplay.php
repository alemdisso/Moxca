<?php
class Moxca_Util_CurrencyDisplay {



    public function FormatCurrency($rawValue)
    {
        if ($rawValue !== null) {
            $currency = new Zend_Currency(
                array(
                    'value' => $rawValue,
                )
            );
        } else {
            $currency = "";
        }


        return $currency;
    }
}

