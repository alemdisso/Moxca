<?php
class Moxca_Form_QuestionEdit extends Moxca_Form_QuestionCreate
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('editQuestionForm')
            ->setAction('/admin/question/edit')
            ->setMethod('post');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addValidator('Int')
            //->addFilter('HtmlEntities')
            ->addFilter('StringTrim');
        $this->addElement($id);


    }

    public function process($data) {

        if ($this->isValid($data) !== true) {
            throw new Moxca_Form_QuestionCreateException('Invalid data!');
        } else {
            $db = Zend_Registry::get('db');
            $questionMapper = new Moxca_Faq_QuestionMapper($db);
            $id = $data['id'];
            $obj = $questionMapper->findById($id);

            $obj->setTitle($data['title']);
            $obj->setStatus($data['status']);
            $obj->setContent($data['content']);
            $obj->setCategory($data['category']);

            $user = Zend_Registry::get('user');
            $obj->setAuthor($user->getId());

            $questionMapper->update($obj);

            return $obj;
        }
    }
 }