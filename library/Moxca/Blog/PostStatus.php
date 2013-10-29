<?php

class Moxca_Blog_PostStatus {

    private $titles = array();


    public function __construct() {
        $this->titles = array(
            Moxca_Blog_PostStatusConstants::STATUS_NIL       => _("#Nil"),
            Moxca_Blog_PostStatusConstants::STATUS_DRAFT     => _("#Draft"),
            Moxca_Blog_PostStatusConstants::STATUS_READY     => _("#Ready"),
            Moxca_Blog_PostStatusConstants::STATUS_PUBLISHED => _("#Published"),
            Moxca_Blog_PostStatusConstants::STATUS_PROTECTED => _("#Protected"),
            Moxca_Blog_PostStatusConstants::STATUS_ARCHIVED  => _("#Archived"),
        );
    }

    public function TitleForType($type)
    {
            switch ($type) {
                case Moxca_Blog_PostStatusConstants::STATUS_NIL:
                case Moxca_Blog_PostStatusConstants::STATUS_DRAFT:
                case Moxca_Blog_PostStatusConstants::STATUS_READY:
                case Moxca_Blog_PostStatusConstants::STATUS_PUBLISHED:
                case Moxca_Blog_PostStatusConstants::STATUS_PROTECTED:
                case Moxca_Blog_PostStatusConstants::STATUS_ARCHIVED:
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
                if ($k != Moxca_Blog_PostStatusConstants::STATUS_NIL) {
                    $data[$k] = $v;
                }
            }
            return($data);
        }
    }
}