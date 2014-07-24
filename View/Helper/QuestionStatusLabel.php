<?php

class Moxca_View_Helper_QuestionStatusLabel extends Zend_View_Helper_Abstract
{
    public function questionStatusLabel(Moxca_Faq_Question $question, Moxca_Faq_QuestionStatus $questionStatus, Zend_View $view)
    {
        $status = $question->getStatus();
        return $view->translate($questionStatus->TitleForType($status));
    }
}

