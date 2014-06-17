<?php

class Moxca_View_Helper_CheckTermFromGet extends Zend_View_Helper_Abstract
{
    public function checkTermFromGet($data, $fieldname="term")
    {
        $filters = array(
//            $fieldname => new Zend_Filter_Alnum(array('allowwhitespace' => true)),
        );
        $validators = array(
            $fieldname => array(new Moxca_Util_ValidTitle()),
        );
        $input = new Zend_Filter_Input($filters, $validators, $data);
        if ($input->isValid()) {
            $term = $input->$fieldname;
            return $term;
        } else {
            die("!!!!");
        }
        throw new Moxca_View_Helper_Exception(_("#Bad value as term."));
    }
}

