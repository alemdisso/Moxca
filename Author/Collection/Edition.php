<?php

class Author_Collection_Edition {

    protected $id;
    protected $work;
    protected $editor;
    protected $serie;
    protected $pages;
    protected $coverImageFilename;
    protected $isbn;
    protected $illustrator;
    protected $coverDesigner;

    function __construct($work, $editor, $id=0) {
        $this->id = (int)$id;
        $this->work = $work;
        $this->editor = $editor;
        $this->pages = null;
        $this->coverImageFileName = null;
        $this->isbn = null;
        $this->illustrator = null;
        $this->coverDesigner = null;
    }

    public function getId() {
        return $this->id;
    } //getId

    public function setId($id) {
        if (($this->id == 0) && ($id > 0)) {
            $this->id = (int)$id;
        } else {
            throw new Author_Collection_EditionException('It\'s not possible to change a edition\'s ID');
        }

    } //SetId

    public function getEditor()
    {
        return $this->editor;
    } //getName

    public function getWork()
    {
        return $this->work;
    } //getWork

    public function getSerie()
    {
        return $this->serie;
    } //getSerie

    public function setSerie($serie)
    {
        $validator = new Moxca_Util_ValidPositiveDecimal();
        if ($validator->isValid($serie)) {
            if ($this->serie != $serie) {
                $this->serie = $serie;
            }
        } else {
            throw new Author_Collection_WorkException("This ($serie) is not a valid serie.");
        }

    } //SetSerie

    public function getPages()
    {
        return $this->pages;
    } //getPages

    public function setPages($pages)
    {
        $validator = new Moxca_Util_ValidPositiveDecimal();
        if ($validator->isValid($pages)) {
            if ($this->pages != $pages) {
                $this->pages = $pages;
            }
        } else {
            throw new Author_Collection_WorkException("This ($pages) is not a valid pages number.");
        }

    } //SetPages



    public function getCover()
    {
        return $this->coverImageFilename;
    } //getCover

    public function setCover($coverImageFilename)
    {
        $validator = new Moxca_Util_ValidFilename();
        if ($validator->isValid($coverImageFilename)) {
            if ($this->coverImageFilename != $coverImageFilename) {
                $this->coverImageFilename = $coverImageFilename;
            }
        } else {
            throw new Author_Collection_WorkException("This ($coverImageFilename) is not a valid coverImageFilename.");
        }

    } //SetCover



    public function getIsbn()
    {
        return $this->isbn;
    } //getIsbn

    public function setIsbn($isbn)
    {
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($isbn)) {
            if ($this->isbn != $isbn) {
                $this->isbn = $isbn;
            }
        } else {
            throw new Author_Collection_WorkException("This ($isbn) is not a valid isbn.");
        }

    } //SetIsbn


    public function getIllustrator()
    {
        return $this->illustrator;
    } //getIllustrator

    public function setIllustrator($illustrator)
    {
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($illustrator)) {
            if ($this->illustrator != $illustrator) {
                $this->illustrator = $illustrator;
            }
        } else {
            throw new Author_Collection_WorkException("This ($illustrator) is not a valid illustrator.");
        }

    } //SetIllustrator


    public function getCoverDesigner()
    {
        return $this->coverDesigner;
    } //getCoverDesigner

    public function setCoverDesigner($coverDesigner)
    {
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($coverDesigner)) {
            if ($this->coverDesigner != $coverDesigner) {
                $this->coverDesigner = $coverDesigner;
            }
        } else {
            throw new Author_Collection_WorkException("This ($coverDesigner) is not a valid coverDesigner.");
        }

    } //SetCoverDesigner


}