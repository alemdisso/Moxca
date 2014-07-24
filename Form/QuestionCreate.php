<?php
class Moxca_Form_QuestionCreate extends Zend_Form
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('newQuestionForm')
            ->setAction('/admin/question/create')
            ->setAttrib('enctype', 'multipart/form-data')
            //->setAction('javascript:callQuestionCreate();')
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

        $element = new Zend_Form_Element_Textarea('question');
        $element->setLabel('#Content:')
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
              ))
            ->setAttrib('rows','3')
            ->setOptions(array('id' => 'richtext'))
            ->setRequired(false)
            ->addFilter('StringTrim');
        $this->addElement($element);

        $element = new Zend_Form_Element_Textarea('answer');
        $element->setLabel('#Answer:')
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

        $element = new Zend_Form_Element_Text('rank');
        $titleValidator = new Moxca_Util_ValidPositiveInteger();
        $element->setLabel(_('#Rank:'))
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
                ->setOptions(array('class' => ''))
                ->addValidator($titleValidator)
                ->addFilter('StringTrim');
        $this->addElement($element);

        $statusObj = new Moxca_Faq_QuestionStatus();
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
            throw new Moxca_Form_QuestionCreateException('Invalid data!');
        } else {
            $db = Zend_Registry::get('db');
            $questionMapper = new Moxca_Faq_QuestionMapper($db);

            $obj = new Moxca_Faq_Question();

            $obj->setTitle($data['title']);
            $obj->setQuestion($data['question']);
            $obj->setAnswer($data['answer']);
            $obj->setRank($data['rank']);
            $obj->setStatus($data['status']);


            $questionMapper->insert($obj);

            return $obj;
        }
    }
 }