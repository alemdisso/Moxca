<?php
class Moxca_Form_PostRemove extends Zend_Form
{
    protected $_buttons = array();

    public function init()
    {

        // initialize form
        $this->setName('removePostForm')
            ->setAction('/admin/post/remove')
            ->setDecorators(array('FormElements',array('HtmlTag', array('tag' => 'div', 'class' => 'Area')),'Form'))
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $this->setButtons(array('Submit'=>_('#Confirm removal'), 'Cancel'=>_('#Don\'t remove')));

    }

    public function process($data) {

        $db = Zend_Registry::get('db');
        $postMapper = new Moxca_Blog_PostMapper($db);

        if ($this->isValid($data) !== true)
        {
            throw new Moxca_Form_PostRemoveException(_('#Invalid data!'));
        }
        else
        {
            $id = $data['id'];
            $post = $postMapper->findById($id);
            $postRemoval = new Moxca_Blog_PostRemoval($post, $postMapper);
            $postRemoval->remove();
            return $id;
        }
    }


  /**
   * Sets a list of buttons - Buttons will be standard submits, or in the getJson() version
   * they are removed from display - but stuck in the json in the .buttons property
   *
   * $buttons = array('save'=>'Save This Thing', 'cancel'=>'Cancel') as an example
   *
   * @param array $buttons
   * @return void
   * @author Corey Frang
   */
  private function setButtons($buttons)
  {
    $this->_buttons = $buttons;
    foreach ($buttons as $name => $label) {
        $this->addElement('submit', $name, array(
            'label'=>$label,
            'class'=> "submit",
            'decorators'=>array('ViewHelper'),
            ));
    }

    $this->addDisplayGroup(array_keys($this->_buttons),'buttons', array(
        'decorators'=>array(
        'FormElements',
        array('HtmlTag', array('tag' => 'div','class' => 'inset-by-nine omega')),

    )
    ));





  }




}