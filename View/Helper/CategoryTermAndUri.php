<?php

class Moxca_View_Helper_CategoryTermAndUri extends Zend_View_Helper_Abstract
{
    public function categoryTermAndUri($termId, Moxca_Blog_TaxonomyMapper $mapper)
    {

        $data = $mapper->getTermAndUri($termId, $mapper);
        return $data;
    }
}

