<?php

class Moxca_Faq_QuestionMapper
{
    protected $db;
    protected $identityMap;

    function __construct()
    {
        $this->db = Zend_Registry::get('db');
        $this->identityMap = new SplObjectStorage;
    }

    public function getAllIds()
    {
        $query = $this->db->prepare('SELECT id FROM moxca_faq_questions WHERE 1=1 ORDER BY rank;');
        $query->execute();
        $resultPDO = $query->fetchAll();

        $result = array();
        foreach ($resultPDO as $row) {
            $result[] = $row['id'];
        }
        return $result;

    }

    public function insert(Moxca_Faq_Question $obj)
    {

        $query = $this->db->prepare("INSERT INTO moxca_faq_questions (title, uri, question,
                            answer, rank, status)
                            VALUES (:title, :uri, :question, :answer, :rank, :status)");

        $query->bindValue(':title', $obj->getTitle(true), PDO::PARAM_STR);
        $query->bindValue(':uri', $obj->getUri(true), PDO::PARAM_STR);
        $query->bindValue(':question', $obj->getQuestion(), PDO::PARAM_STR);
        $query->bindValue(':answer', $obj->getAnswer(), PDO::PARAM_STR);
        $query->bindValue(':rank', $obj->getRank(), PDO::PARAM_INT);
        $query->bindValue(':status', $obj->getStatus(), PDO::PARAM_INT);


        $query->execute();

        $obj->setId((int)$this->db->lastInsertId());
        $this->identityMap[$obj] = $obj->getId();
    }

    public function update(Moxca_Faq_Question $obj)
    {
        if (!isset($this->identityMap[$obj])) {
            throw new Moxca_Faq_QuestionMapperException('Object has no ID, cannot update.');
        }

        $query = $this->db->prepare("UPDATE moxca_faq_questions SET title = :title, uri = :uri
            , question = :question, answer = :answer
            , rank = :rank, status = :status WHERE id = :id;");

        $query->bindValue(':title', $obj->getTitle(true), PDO::PARAM_STR);
        $query->bindValue(':uri', $obj->getUri(true), PDO::PARAM_STR);
        $query->bindValue(':question', $obj->getQuestion(), PDO::PARAM_STR);
        $query->bindValue(':answer', $obj->getAnswer(), PDO::PARAM_STR);
        $query->bindValue(':rank', $obj->getRank(), PDO::PARAM_STR);
        $query->bindValue(':status', $obj->getStatus(), PDO::PARAM_STR);
        $query->bindValue(':id', $this->identityMap[$obj], PDO::PARAM_STR);

        try {
            $query->execute();
        } catch (Exception $e) {
            throw new Moxca_Faq_QuestionException("sql failed");
        }
    }

    public function findById($id)
    {
        $this->identityMap->rewind();
        while ($this->identityMap->valid()) {
            if ($this->identityMap->getInfo() == $id) {
                return $this->identityMap->current();
            }
            $this->identityMap->next();
        }

        $query = $this->db->prepare('SELECT title, uri, question, answer, rank, status
                            FROM moxca_faq_questions WHERE id = :id;');
        $query->bindValue(':id', $id, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch();

        if (empty($result)) {
            throw new Moxca_Faq_QuestionMapperException(sprintf('There is no question with id #%d.', $id));
        }
        $uri = $result['uri'];

        $obj = new Moxca_Faq_Question();
        $this->setAttributeValue($obj, $id, 'id');
        $this->setAttributeValue($obj, $result['title'], 'title');
        $this->setAttributeValue($obj, $result['uri'], 'uri');
        $this->setAttributeValue($obj, $result['question'], 'question');
        $this->setAttributeValue($obj, $result['answer'], 'answer');
        $this->setAttributeValue($obj, $result['rank'], 'rank');
        $this->setAttributeValue($obj, $result['status'], 'status');

        $this->identityMap[$obj] = $id;

        return $obj;

    }

    public function findByUri($uri)
    {
        $query = $this->db->prepare('SELECT id FROM moxca_faq_questions WHERE uri = :uri LIMIT 1;');
        $query->bindValue(':uri', $uri, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch();

        if (empty($result)) {
            throw new Moxca_Faq_QuestionMapperException(sprintf('There is no question with uri #%s.', $uri));
        }
        $id = $result['id'];

        if ($id > 0) {
            return $this->findById($id);
        } else {
            throw new Moxca_Faq_QuestionMapperException(sprintf('The question with id #%s has id=0?!?.', $uri));
        }

    }

    public function delete(Moxca_Faq_Question $obj)
    {
        if (!isset($this->identityMap[$obj])) {
            throw new Moxca_Faq_QuestionMapperException('Object has no ID, cannot delete.');
        }
        $query = $this->db->prepare('DELETE FROM moxca_faq_questions WHERE id = :id;');
        $query->bindValue(':id', $this->identityMap[$obj], PDO::PARAM_STR);
        $query->execute();

        unset($this->identityMap[$obj]);
    }


    public function getAllActiveQuestionsIds()
    {
        $query = $this->db->prepare('SELECT p.id FROM moxca_faq_questions p
                                     WHERE status = :status ORDER BY rank ASC;');
        $query->bindValue(':status', Moxca_Blog_QuestionStatusConstants::STATUS_ACTIVE, PDO::PARAM_INT);
        $query->execute();
        $resultPDO = $query->fetchAll();

        $result = array();
        foreach ($resultPDO as $row) {
            if (!is_null($row['id'])) {
                $result[] = $row['id'];
            }
        }
        return $result;

    }

    public function getAllActiveQuestionsIdsAndTitles()
    {
        $query = $this->db->prepare('SELECT p.id, p.title FROM moxca_faq_questions p
                                     WHERE status = :status ORDER BY rank ASC;');
        $query->bindValue(':status', Moxca_Faq_QuestionStatusConstants::STATUS_ACTIVE, PDO::PARAM_INT);
        $query->execute();
        $resultPDO = $query->fetchAll();

        $result = array();
        foreach ($resultPDO as $row) {
            if (!is_null($row['id'])) {
                $result[] = array('id' => $row['id'], 'title' => $row['title'],);
            }
        }
        return $result;

    }



    private function setAttributeValue(Moxca_Faq_Question $a, $fieldValue, $attributeName)
    {
        $attribute = new ReflectionProperty($a, $attributeName);
        $attribute->setAccessible(TRUE);
        $attribute->setValue($a, $fieldValue);
    }

}