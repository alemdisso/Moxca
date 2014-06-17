<?php
class Moxca_Util_CheckIdFromGet {

    public function check(Zend_Controller_Request_Http $request, $elementId='id')
    {
        $data = $request->getParams();
        $filters = array(
            $elementId => new Zend_Filter_Alnum(),
        );
        $validators = array(
            $elementId => array('Digits', new Zend_Validate_GreaterThan(0)),
        );
        $input = new Zend_Filter_Input($filters, $validators, $data);
        if ($input->isValid()) {
            $id = $input->id;
            return $id;
        }
        throw new Moxca_Util_Exception("Invalid Id from Get");

    }
}

