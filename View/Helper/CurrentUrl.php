<?php

class Moxca_View_Helper_CurrentUrl extends Zend_View_Helper_Abstract
{
    public function currentUrl()
    {
        $pageURL = 'http';
        if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")) {
            $pageURL .= "s";

        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
}

