<?php

class Moxca_Faq_QuestionStatus {

    private $titles = array();


    public function __construct() {
        $this->titles = array(
            Moxca_Faq_QuestionStatusConstants::STATUS_NIL      => _("#Nil"),
            Moxca_Faq_QuestionStatusConstants::STATUS_DRAFT    => _("#Draft"),
            Moxca_Faq_QuestionStatusConstants::STATUS_ACTIVE   => _("#Active"),
            Moxca_Faq_QuestionStatusConstants::STATUS_ARCHIVED => _("#Archived"),
        );
    }

    public function TitleForType($type)
    {
            switch ($type) {
                case Moxca_Faq_QuestionStatusConstants::STATUS_NIL:
                case Moxca_Faq_QuestionStatusConstants::STATUS_DRAFT:
                case Moxca_Faq_QuestionStatusConstants::STATUS_ACTIVE:
                case Moxca_Faq_QuestionStatusConstants::STATUS_ARCHIVED:
                    return $this->titles[$type];
                    break;

                default:
                    return _("#Unknown type");
                    break;
            }
    }

    public function AllTitles($includeNull = false)
    {

        if ($includeNull) {
            return $this->titles;
        } else {
            $data = array();
            foreach ($this->titles as $k => $v) {
                if ($k != Moxca_Faq_QuestionStatusConstants::STATUS_NIL) {
                    $data[$k] = $v;
                }
            }
            return($data);
        }
    }
}