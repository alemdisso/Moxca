<?php
class Moxca_Form_CategoryCreate extends Zend_Form
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('categoryCreateForm')
            ->setAction('/admin/category/create')
            ->setMethod('post');

        $element = new Zend_Form_Element_Text('newCategory');
        $validator = new Moxca_Util_ValidString();
        $element->setLabel(_('#New category:'))
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
                ->setOptions(array('class' => ''))
                ->addValidator($validator)
                ->addFilter('StringTrim');
        $this->addElement($element);

        // create submit button
        $element = new Zend_Form_Element_Submit('submit');
        $element->setLabel('#Submit') //Gravar
               ->setDecorators(array('ViewHelper','Errors',
                    array(array('data' => 'HtmlTag'),
                    array('tag' => 'div','class' => '')),
                  ))
               ->setOptions(array('class' => ''));
        $this->addElement($element);



    }

    public function process($data) {

        if ($this->isValid($data) !== true) {
            throw new Moxca_Form_TaxonomyException('Invalid data!');
        } else {
            $db = Zend_Registry::get('db');
            if ($data['newCategory'] != "") {
                $taxonomyMapper = new Moxca_Blog_TaxonomyMapper($db);
                $termId = $taxonomyMapper->findTermAndInsertIfNew($data['newCategory']);
                $taxonomyMapper->insertCategory($termId);
            } else {
                throw new Moxca_Form_TaxonomyException('Empty category!');

            }

        }
    }
 }