<?php
class Moxca_Form_PostCreate extends Zend_Form
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('newPostForm')
            ->setAction('/admin/post/create')
            ->setAttrib('enctype', 'multipart/form-data')
            //->setAction('javascript:callPostCreate();')
            ->setElementDecorators(array('FormElements',array('HtmlTag', array('tag' => 'div', 'class' => 'form')),'Form'))
            ->setMethod('post');

        $element = new Zend_Form_Element_Text('title');
        $titleValidator = new Moxca_Util_ValidTitle();
        $element->setLabel(_('#Title:'))
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin clear_both')),
                ))
//                ->setOptions(array('class' => ''))
                ->setRequired(true)
                ->addErrorMessage(_("#Title is required"))
                ->addValidator($titleValidator)
                ->addFilter('StringTrim');
        $this->addElement($element);

        $statusObj = new Moxca_Blog_PostStatus();
        $titlesArray = $statusObj->allTitles();

        $element = new Zend_Form_Element_Select('status');
	$element->setLabel('#Status')
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'option inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
		->setMultiOptions($titlesArray)
                ->setOptions(array('class' => 'choose'))
                ->setSeparator('');
        $this->addElement($element);


        $mapper = new Moxca_Taxonomy_TaxonomyMapper();
        $rawLabelsArray = $mapper->getAllCategoriesAlphabeticallyOrdered();

        $view = new Zend_View();
        $labelsArray = array("0" => $view->translate("#(choose)"));

        foreach($rawLabelsArray as $k => $label) {
            $labelsArray[$k] = $label;
        }

        $element = new Zend_Form_Element_Select('category');
        $element->setLabel('#Category')
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
		->setMultiOptions($labelsArray)
                ->setOptions(array('class' => 'choose'))
                ->setRegisterInArrayValidator(false);
        $this->addElement($element);

        $element = new Zend_Form_Element_Textarea('content');
        $element->setLabel('#Content:')
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
              ))
            ->setAttrib('rows','8')
            ->setOptions(array('id' => 'richtext'))
            ->setRequired(false)
            ->addFilter('StringTrim');
        $this->addElement($element);

         // create submit button
        $element = new Zend_Form_Element_Submit('submit');
        $element->setLabel('#Submit') //Gravar
               ->setDecorators(array('ViewHelper','Errors',
                    array(array('data' => 'HtmlTag'),
                    array('tag' => 'div','class' => '')),
//                    array('Label',
//                      array('tag' => 'div','tagClass' => '')
//                    ),
                  ))
               ->setOptions(array('class' => 'submit'));
        $this->addElement($element);
    }

    public function process($data) {

        if ($this->isValid($data) !== true) {
            throw new Moxca_Form_PostCreateException('Invalid data!');
        } else {
            $db = Zend_Registry::get('db');
            $postMapper = new Moxca_Blog_PostMapper($db);

            $post = new Moxca_Blog_Post();

            $post->setTitle($data['title']);
            $post->setStatus($data['status']);
            $post->setContent($data['content']);
            $post->setCategory($data['category']);

            $post->setCreationDate(date("Y-m-d H:i:s", time()));
            //$post->setPublicationDate(date("Y-m-d H:i:s", time()));

            $user = Zend_Registry::get('user');
            $post->setAuthor($user->getId());

            $postMapper->insert($post);

            return $post;
        }
    }
 }