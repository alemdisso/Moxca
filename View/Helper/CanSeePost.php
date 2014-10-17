<?php

class Moxca_View_Helper_CanSeePost extends Zend_View_Helper_Abstract
{
    public function canSeePost(Moxca_Blog_Post $post)
    {
        switch ($post->getStatus()) {
            case Moxca_Blog_PostStatusConstants::STATUS_PUBLISHED:
            case Moxca_Blog_PostStatusConstants::STATUS_ARCHIVED:
                return true;
                break;

            case Moxca_Blog_PostStatusConstants::STATUS_DRAFT:
            case Moxca_Blog_PostStatusConstants::STATUS_PROTECTED:
            default:
                try {
                    $checker = new Moxca_Access_PrivilegeChecker("blog", "post", "explore-not-published");
                    return true;
                } catch (Exception $e) {
                    return false;
                }
                break;
        }
    }
}

