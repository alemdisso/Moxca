<?php

class Moxca_View_Helper_PostStatusLabel extends Zend_View_Helper_Abstract
{
    public function postStatusLabel(Moxca_Blog_Post $post, Moxca_Blog_PostStatus $postStatus, Zend_View $view)
    {
        $status = $post->getStatus();
        return $view->translate($postStatus->TitleForType($status));
    }
}

