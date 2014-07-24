<?php

class Moxca_Faq_Question {

    protected $id;
    protected $title;
    protected $uri;
    protected $question;
    protected $answer;
    protected $rank;
    protected $status;

    function __construct($id=0) {
        $this->id = (int)$id;
        $this->title = "";
        $this->uri = "";
        $this->question = "";
        $this->answer = "";
        $this->rank = null;
        $this->status = null;
    }

    public function getId() {
        return $this->id;

    } //getId

    public function setId($id) {
        if (($this->id == 0) && ($id > 0)) {
            $this->id = (int)$id;
        } else {
            throw new Moxca_Faq_QuestionException('It\'s not possible to change a question\'s ID');
        }

    } //SetId

    public function getRank()
    {
        return $this->rank;
    } //getRank

    public function setRank($rank)
    {
        $validator = new Moxca_Util_ValidPositiveDecimal();
        if ($validator->isValid($rank)) {
            if ($this->rank != $rank) {
                $this->rank = $rank;
            }
        } else {
            throw new Moxca_Faq_QuestionException("This ($rank) is not a valid rank id.");
        }
    } //SetRank

    public function getAnswer()
    {
        return $this->answer;
    } //getAnswer

    public function setAnswer($answer)
    {
        $validator = new Moxca_Util_ValidMarkup();
        if ($validator->isValid($answer)) {
            if ($this->answer != $answer) {
                $this->answer = $answer;
            }
        } else {
            throw new Moxca_Faq_QuestionException("This ($answer) is not a valid answer.");
        }
    } //SetAnswer

    public function getQuestion()
    {
        return $this->question;
    } //getQuestion

    public function setQuestion($question)
    {
        $validator = new Moxca_Util_ValidLongString();
        if ($validator->isValid($question)) {
            if ($this->question != $question) {
                $this->question = $question;
            }
        } else {
            throw new Moxca_Faq_QuestionException("This ($question) is not a valid question.");
        }
    } //SetQuestion

    public function getTitle()
    {
        return $this->title;
    } //getTitle

    public function setTitle($title)
    {
        $prefix = "";
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($title)) {
            if ($this->title != $title) {

                $this->title = $title;
                $converter = new Moxca_Util_StringToAscii();
                $this->uri = $converter->toAscii($this->getTitle());

            }
        } else {
            throw new Moxca_Faq_QuestionException("This ($title) is not a valid title.");
        }

    } //SetTitle

    public function setStatus($status)
    {
        if ($status != $this->status) {
            switch ($status) {
                case Moxca_Faq_QuestionStatusConstants::STATUS_NIL:
                case Moxca_Faq_QuestionStatusConstants::STATUS_DRAFT:
                case Moxca_Faq_QuestionStatusConstants::STATUS_ACTIVE:
                case Moxca_Faq_QuestionStatusConstants::STATUS_ARCHIVED:
                    $this->status = (int)$status;
                    break;

                case null:
                case "":
                case 0:
                case false:
                    $this->status = null;
                    break;

                default:
                    throw new Moxca_Faq_QuestionException("Invalid project status.");
                    break;
            }
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getUri()
    {
        return $this->uri;
    } //getUri

}