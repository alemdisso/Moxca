<?php

class Moxca_View_Helper_CheckIdFromGet extends Zend_View_Helper_Abstract
{
    public function checkIdFromGet($data, $fieldname="id")
    {
        $filters = array(
            $fieldname => new Zend_Filter_Alnum(),
        );
        $validators = array(
            $fieldname => array('Digits', new Zend_Validate_GreaterThan(0)),
        );
        $input = new Zend_Filter_Input($filters, $validators, $data);
        if ($input->isValid()) {
            $id = $input->$fieldname;
            return $id;
        }
        throw new Moxca_View_Helper_Exception(_("#Bad value to Id."));
    }
}

