<?php
class Moxca_Form_PostEdit extends Moxca_Form_PostCreate
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('editPostForm')
            ->setAction('/admin/post/edit')
            ->setMethod('post');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addValidator('Int')
            //->addFilter('HtmlEntities')
            ->addFilter('StringTrim');
        $this->addElement($id);


    }

    public function process($data) {

        if ($this->isValid($data) !== true) {
            throw new Moxca_Form_PostCreateException('Invalid data!');
        } else {
            $db = Zend_Registry::get('db');
            $postMapper = new Moxca_Blog_PostMapper($db);
            $id = $data['id'];
            $post = $postMapper->findById($id);

            $post->setTitle($data['title']);
            $post->setStatus($data['status']);
            $post->setContent($data['content']);
            $post->setCategory($data['category']);

            $user = Zend_Registry::get('user');
            $post->setAuthor($user->getId());

            $postMapper->update($post);

            return $post;
        }
    }
 }