<?php

class Moxca_View_Helper_PostKeywordsLabels extends Zend_View_Helper_Abstract
{
    public function postKeywordsLabels($workId, Moxca_Blog_TaxonomyMapper $mapper)
    {
        $keywordsTermsAndUris = $mapper->getKeywordsRelatedToPost($workId);
        $keywordsLabels = array();
        foreach($keywordsTermsAndUris as $keywordUri => $keywordLabel) {


            $keywordsLabels[$keywordUri] = array('label' => $keywordLabel, 'uri' => $keywordUri);
        }
        return $keywordsLabels;
    }
}

