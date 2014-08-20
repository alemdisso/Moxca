<?php

class Moxca_View_Helper_CheckUriFromGet extends Zend_View_Helper_Abstract
{
    public function CheckUriFromGet($data)
    {
        $validator = new Moxca_Util_ValidUri();

        if ($validator->isValid($data['uri'])) {
            $uri = $data['uri'];
            return $uri;
        }
        throw new Moxca_Util_Exception("Invalid Uri from Get");
    }
}

