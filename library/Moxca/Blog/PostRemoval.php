<?php

class Moxca_Blog_PostRemoval {

    private $post;
    private $postMapper;

    public function __construct(Moxca_Blog_Post $post, Moxca_Blog_PostMapper $postMapper)
    {
        $this->post = $post;
        $this->postMapper = $postMapper;
    }

    public function canBeRemoved()
    {
        $can=true;
        return $can;

    }

    public function remove()
    {
        //check if can really remove
        if ($this->canBeRemoved()) {

            // delete post
            $this->postMapper->delete($this->post);

        } else {
            throw new Moxca_Blog_PostException("This post can't be removed");
        }



    }

}
