<?php

class Author_Collection_Work {

    protected $id;
    protected $title;
    protected $uri;
    protected $summary;
    protected $description;
    protected $type;

    function __construct($id=0) {
        $this->id = (int)$id;
        $this->title = "";
        $this->uri = "";
        $this->summary = "";
        $this->description = "";
        $this->type = null;
    }

    public function getId() {
        return $this->id;

    } //getId

    public function setId($id) {
        if (($this->id == 0) && ($id > 0)) {
            $this->id = (int)$id;
        } else {
            throw new Author_Collection_WorkException('It\'s not possible to change a work\'s ID');
        }

    } //SetId

    public function getDescription()
    {
        return $this->description;
    } //getDescription

    public function setDescription($description)
    {
        $validator = new Moxca_Util_ValidLongString();
        if ($validator->isValid($description)) {
            if ($this->description != $description) {
                $this->description = $description;
            }
        } else {
            throw new Author_Collection_WorkException("This ($description) is not a valid description.");
        }
    } //SetDescription

    public function getSummary()
    {
        return $this->summary;
    } //getSummary

    public function setSummary($summary)
    {
        $validator = new Moxca_Util_ValidLongString();
        if ($validator->isValid($summary)) {
            if ($this->summary != $summary) {
                $this->summary = $summary;
            }
        } else {
            throw new Author_Collection_WorkException("This ($summary) is not a valid summary.");
        }
    } //SetSummary

    public function getTitle()
    {
        return $this->title;
    } //getTitle

    public function setTitle($title)
    {
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($title)) {
            if ($this->title != $title) {
                $this->title = $title;
                $converter = new Moxca_Util_StringToAscii();
                $this->uri = $converter->toAscii($this->title);
            }
        } else {
            throw new Author_Collection_WorkException("This ($title) is not a valid title.");
        }

    } //SetTitle

    public function setType($type)
    {
        switch ($type) {
            case Author_Collection_WorkTypeConstants::TYPE_NIL:
            case Author_Collection_WorkTypeConstants::TYPE_CHILDREN:
            case Author_Collection_WorkTypeConstants::TYPE_YOUNG:
            case Author_Collection_WorkTypeConstants::TYPE_FICTION:
            case Author_Collection_WorkTypeConstants::TYPE_ESSAY:
                $this->type = (int)$type;
                break;

            case null:
            case "":
            case 0:
            case false:
                $this->type = null;
                break;

            default:
                throw new Author_Collection_ProjectException("Invalid project type.");
                break;
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function getUri()
    {
        return $this->uri;
    } //getUri

}