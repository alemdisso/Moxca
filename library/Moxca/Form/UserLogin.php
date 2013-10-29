<?php
class Moxca_Form_UserLogin extends Zend_Form
{
    public function init()
    {

        // initialize form
        $this->setName('loginForm')
            ->setAction('/auth/login')
            ->setDecorators(array('FormElements',array('HtmlTag', array('tag' => 'div', 'class' => 'form')),'Form'))
            ->setMethod('post');

        // create text input for name
        $login = new Zend_Form_Element_Text('loginLogin');
        $loginValidator = new Moxca_Util_ValidName();
        $login->setLabel(_('#User:'))
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                  array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                  array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
              ))
//            ->setOptions(array('class' => ''))
            ->setRequired(true)
            ->addValidator($loginValidator)
            ->addFilter('StringTrim')
                ;
        // attach elements to form
        $this->addElement($login);

        // create text input for name
        $password = new Zend_Form_Element_Password('passwordLogin');
        $passwordValidator = new Moxca_Util_ValidString();
        $password->setLabel(_('#Password:'))
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                  array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'inputAdmin')),
                  array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
              ))
//            ->setOptions(array('class' => ''))
            ->setRequired(true)
            ->addValidator($passwordValidator)
            ->addFilter('StringTrim')
                ;
        // attach elements to form
        $this->addElement($password);

        // create submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel(_('#Login'))
               ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                  array(array('data' => 'HtmlTag'),
                  array('tag' => 'div', 'class' => '')),
               ))
               ->setOptions(array('class' => 'submit'));
        $this->addElement($submit);

    }

    public function process($data) {
        if ($this->isValid($data) !== true)
        {
            throw new Moxca_Form_UserLoginException(_('#Invalid data!'));
        }
        else
        {

            $adapter = new Moxca_Auth_Adapter_Mapper($this->loginLogin->GetValue(), $this->passwordLogin->GetValue());
            $auth = Zend_Auth::getInstance();
            $result = $auth->authenticate($adapter);
            try {
                $user = $adapter->getAuthenticatedUser();
            }
            catch (Exception $e) {
                return false;
            }

            if ($user instanceOf Moxca_Auth_User) {
                return $user;
            } else {

                return false;
            }
        }
    }

    public function successAction()
    {
        if ($this->_helper->getHelper('FlashMessenger')->getMessages()) {
            $this->view->messages = $this->_helper
                ->getHelper('FlashMessenger')
                ->getMessages();
        } else {
            $this->_redirect('/');
        }
    }

}
