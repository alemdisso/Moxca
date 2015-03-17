<?php
class Moxca_Form_UserCreate extends Zend_Form
{
    public function init()
    {

        // initialize form
        $this->setName('newUserForm')
            ->setAction('/auth/user/create')
            ->setDecorators(array('FormElements',array('HtmlTag', array('tag' => 'div', 'class' => 'Area')),'Form'))
            ->setMethod('post');

        $name = new Zend_Form_Element_Text('name');
        $nameValidator = new Moxca_Util_ValidString();
        $name->setLabel(_('#Name:'))
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                  array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'eight columns')),
                  array('Label', array('tag' => 'div', 'tagClass' => 'three columns alpha Right')),
              ))
            ->setOptions(array('class' => 'Full alpha omega'))
            ->setRequired(true)
            ->addErrorMessage(_("#Name is required"))
            ->addValidator($nameValidator)
            ->addFilter('StringTrim')
                ;
        // attach elements to form
        $this->addElement($name);

        $role = new Zend_Form_Element_Select('role');
        $role->setLabel(_('#Role'))
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                  array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'two columns omega')),
                  array('Label', array('tag' => 'div', 'tagClass' => 'one column Right')),
              ))
              ->setOptions(array('class' => 'Full alpha omega'))
                ->addErrorMessage(_("#Role is required"))
              ->setRequired(true);
        $rolesObj = new Moxca_Access_Roles();
        $roles = $rolesObj->AllRoles();
        $role->addMultiOption(null, "(escolha um tipo)");
        while (list($key, $title) = each($roles)) {
            $role->addMultiOption($key, $title);
        }
        $this->addElement($role);

        $login = new Zend_Form_Element_Text('login');
        $loginValidator = new Moxca_Util_ValidString();
        $login->setLabel(_('#Login Name:'))
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                  array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'two columns')),
                  array('Label', array('tag' => 'div', 'tagClass' => 'three columns alpha Right')),
              ))
            ->setOptions(array('class' => 'Full alpha omega'))
                ->addErrorMessage(_("#Login is required"))
            ->setRequired(true)
            ->addValidator($loginValidator)
            ->addFilter('StringTrim')
                ;
        // attach elements to form
        $this->addElement($login);

        $password = new Zend_Form_Element_Password('password');
        $passwordValidator = new Moxca_Util_ValidString();
        $password->setLabel(_('#Password:'))
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                  array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'two columns')),
                  array('Label', array('tag' => 'div', 'tagClass' => 'one column Right')),
              ))
                ->addErrorMessage(_("#Password is required"))
            ->setOptions(array('class' => 'Full alpha omega'))
            ->setRequired(true)
            ->addValidator($passwordValidator)
            ->addFilter('StringTrim')
                ;
        // attach elements to form
        $this->addElement($password);

        $email = new Zend_Form_Element_Text('email');
        $emailValidator = new Moxca_Util_ValidEmail();
        $email->setLabel(_('#Email:'))
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                  array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'five columns omega')),
                  array('Label', array('tag' => 'div', 'tagClass' => 'one column Right')),
              ))
            ->setOptions(array('class' => 'Full alpha omega'))
                ->addErrorMessage(_("#Email is required"))
            ->setRequired(true)
            ->addValidator($emailValidator)
            ->addFilter('StringTrim')
                ;
        // attach elements to form
        $this->addElement($email);

        // create submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit ->setLabel(_('#Submit'))
                ->setDecorators(array('ViewHelper','Errors',
                    array(array('data' => 'HtmlTag'),
                    array('tag' => 'div','class' => 'two columns inset-by-nine omega')),
                    array('Label',
                      array('tag' => 'div','tagClass' => 'three columns alpha Invisible')
                    ),
                  ))
                ->setOptions(array('class' => 'submit Full alpha omega'));
        $this   ->addElement($submit);

    }

    public function process($data) {
        if ($this->isValid($data) !== true)
        {
            throw new Moxca_Form_UserCreateException(_('#Invalid data!'));
        }
        else
        {
            $db = Zend_Registry::get('db');
            $userMapper = new Moxca_Auth_UserMapper($db);
            $user = new Moxca_Auth_User();
            $user->SetLogin($this->login->GetValue());
            $user->SetName($this->name->GetValue());
            $user->SetPassword($this->password->GetValue());
            $user->SetEmail($this->email->GetValue());
            $user->SetRole($this->role->GetValue());
            $userMapper->insert($user);
        }
    }
}