<?php

class Moxca_Blog_CategoryMapper
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
        $query = $this->db->prepare('SELECT id FROM moxca_blog_categories WHERE 1=1;');
        $query->execute();
        $resultPDO = $query->fetchAll();

        $result = array();
        foreach ($resultPDO as $row) {
            $result[] = $row['id'];
        }
        return $result;

    }

    public function insert(Moxca_Blog_Category $obj)
    {

        $query = $this->db->prepare("INSERT INTO moxca_blog_categories (label)
            VALUES (:label)");

        $query->bindValue(':label', $obj->getLabel(), PDO::PARAM_STR);

        $query->execute();

        $obj->setId((int)$this->db->lastInsertId());
        $this->identityMap[$obj] = $obj->getId();

    }

    public function update(Moxca_Blog_Category $obj)
    {
        if (!isset($this->identityMap[$obj])) {
            throw new Moxca_Blog_CategoryMapperException('Object has no ID, cannot update.');
        }

        $query = $this->db->prepare("UPDATE moxca_blog_categories SET label = :label WHERE id = :id;");

        $query->bindValue(':label', $obj->getLabel(), PDO::PARAM_STR);

        try {
            $query->execute();
        } catch (Exception $e) {
            throw new Moxca_Blog_CategoryException("sql failed");
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

        $query = $this->db->prepare('SELECT label FROM moxca_blog_categories WHERE id = :id;');
        $query->bindValue(':id', $id, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch();

        if (empty($result)) {
            throw new Moxca_Blog_CategoryMapperException(sprintf('There is no category with id #%d.', $id));
        }
        $uri = $result['uri'];

        $obj = new Moxca_Blog_Category();
        $this->setAttributeValue($obj, $id, 'id');
        $this->setAttributeValue($obj, $result['label'], 'label');

        $this->identityMap[$obj] = $id;

        return $obj;

    }

    public function delete(Moxca_Blog_Category $obj)
    {
        if (!isset($this->identityMap[$obj])) {
            throw new Moxca_Blog_CategoryMapperException('Object has no ID, cannot delete.');
        }
        $query = $this->db->prepare('DELETE FROM moxca_blog_categories WHERE id = :id;');
        $query->bindValue(':id', $this->identityMap[$obj], PDO::PARAM_STR);
        $query->execute();
        unset($this->identityMap[$obj]);
    }


    public function getAllCategoriesAlphabeticallyOrdered()
    {
        $query = $this->db->prepare('SELECT id, label FROM moxca_blog_categories WHERE 1 = 1 ORDER BY label;');
        $query->execute();
        $resultPDO = $query->fetchAll();

        $data = array();
        foreach ($resultPDO as $row) {
            $data[$row['id']] = $row['label'];
        }
        return $data;

    }

    private function setAttributeValue(Moxca_Blog_Category $a, $fieldValue, $attributeName)
    {
        $attribute = new ReflectionProperty($a, $attributeName);
        $attribute->setAccessible(TRUE);
        $attribute->setValue($a, $fieldValue);
    }


}