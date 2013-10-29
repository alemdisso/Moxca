<?php

class Moxca_Blog_Category {

    protected $id;
    protected $label;

    function __construct($id=0) {
        $this->id = (int)$id;
        $this->label = null;
    }

    public function getId() {
        return $this->id;
    } //getId

    public function setId($id) {
        if (($this->id == 0) && ($id > 0)) {
            $this->id = (int)$id;
        } else {
            throw new Moxca_Blog_CategoryException('It\'s not possible to change a category\'s ID');
        }
    } //SetId

    public function getEditor()
    {
        return $this->editor;
    } //getEditor

    public function setEditor($editor)
    {
        $validator = new Moxca_Util_ValidPositiveDecimal();
        if ($validator->isValid($editor)) {
            if ($this->pages != $editor) {
                $this->pages = $editor;
            }
        } else {
            throw new Moxca_Blog_WorkException("This ($editor) is not a valid editor.");
        }

    } //SetEditor

    public function getLabel()
    {
        return $this->label;
    } //getLabel

    public function setLabel($label)
    {
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($label)) {
            if ($this->label != $label) {
                $this->label = $label;
            }
        } else {
            throw new Moxca_Blog_CategoryException("This ($label) is not a valid label.");
        }

    } //SetLabel

}
