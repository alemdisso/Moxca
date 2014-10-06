<?php
class Moxca_Form_PostKeywordAdd extends Zend_Form
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('postKeywordAddForm')
            ->setAction('/admin/post/create-keyword')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $mapper = new Moxca_Blog_TaxonomyMapper();
        $rawLabelsArray = $mapper->getAllPostsKeywordsAlphabeticallyOrdered();

        $view = new Zend_View();
        $keywordsArray = array("0" => $view->translate("#(choose)"));

        foreach($rawLabelsArray as $k => $tagArray) {
            $keywordsArray[$k] = $tagArray['term'];
        }


        $element = new Zend_Form_Element_Select('existingKeyword');
        $element->setLabel('#Keywords')
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
		->setMultiOptions($keywordsArray)
                ->setOptions(array('class' => 'choose'))
                ->setRegisterInArrayValidator(false);
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('newKeyword');
        $validator = new Moxca_Util_ValidString();
        $element->setLabel(_('#New keyword:'))
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
            $postMapper = new Moxca_Blog_PostMapper($db);

            $postId = $data['id'];
            $postObj = $postMapper->findById($postId);

            if ($data['existingKeyword'] > 0) {
                $postObj->addKeyword($data['existingKeyword']);

            } else if ($data['newKeyword'] != "") {
                $taxonomyMapper = new Moxca_Blog_TaxonomyMapper($db);

                $keywords = preg_split( "/(,|;|\|)/", $data['newKeyword'] );

                foreach ($keywords as $eachKeyword) {
                    $eachKeyword = trim($eachKeyword);
                    $termId = $taxonomyMapper->findTermAndInsertIfNew($eachKeyword);
                    $postObj->addKeyword($termId);
                }
            }

            $postMapper->update($postObj);
            return $postObj;
        }
    }
 }