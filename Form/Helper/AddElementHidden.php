<?php

class Moxca_Form_Helper_AddElementHidden extends Zend_View_Helper_Abstract
{
    public function addElementHidden(Zend_Form $form, string $id)
    {
        $element = new Zend_Form_Element_Hidden($id);
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $form->addElement($element);
    }
}

