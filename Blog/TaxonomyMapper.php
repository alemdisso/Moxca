<?php

class Moxca_Blog_TaxonomyMapper
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

    public function insert(Moxca_Blog_Taxonomy $obj)
    {

        $query = $this->db->prepare("INSERT INTO moxca_blog_categories (label)
            VALUES (:label)");

        $query->bindValue(':label', $obj->getLabel(), PDO::PARAM_STR);

        $query->execute();

        $obj->setId((int)$this->db->lastInsertId());
        $this->identityMap[$obj] = $obj->getId();

    }

    public function insertCategory($termId)
    {

        $query = $this->db->prepare("INSERT INTO moxca_blog_terms_taxonomy (term_id, taxonomy, count)
            VALUES (:termId, 'category', 0)");

        $query->bindValue(':termId', $termId, PDO::PARAM_INT);

        $query->execute();

    }

    public function insertPostCategoryRelationShip(Moxca_Blog_Post $obj)
    {

        $termTaxonomy = $this->existsCategory($obj->getCategory());
        if (!$termTaxonomy) {
            $termTaxonomy = $this->insertCategory($obj->getCategory());
        }
        $query = $this->db->prepare("INSERT INTO moxca_blog_term_relationships (object, term_taxonomy)
            VALUES (:postId, :termTaxonomy)");

        $query->bindValue(':postId', $obj->getId(), PDO::PARAM_STR);
        $query->bindValue(':termTaxonomy', $termTaxonomy, PDO::PARAM_STR);

        $query->execute();


    }

    public function existsCategory($termId)
    {
        $query = $this->db->prepare("SELECT id FROM moxca_blog_terms_taxonomy WHERE term_id = :termId AND taxonomy = 'category';");

        $query->bindValue(':termId', $termId, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch();

        if (!empty($result)) {
            $row = current($result);
            return $row['id'];
        } else {
            return false;
        }
    }

    public function update(Moxca_Blog_Taxonomy $obj)
    {
        if (!isset($this->identityMap[$obj])) {
            throw new Moxca_Blog_TaxonomyMapperException('Object has no ID, cannot update.');
        }

        $query = $this->db->prepare("UPDATE moxca_blog_categories SET label = :label WHERE id = :id;");

        $query->bindValue(':label', $obj->getLabel(), PDO::PARAM_STR);

        try {
            $query->execute();
        } catch (Exception $e) {
            throw new Moxca_Blog_TaxonomyException("sql failed");
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
            throw new Moxca_Blog_TaxonomyMapperException(sprintf('There is no taxonomy with id #%d.', $id));
        }
        $uri = $result['uri'];

        $obj = new Moxca_Blog_Taxonomy();
        $this->setAttributeValue($obj, $id, 'id');
        $this->setAttributeValue($obj, $result['label'], 'label');

        $this->identityMap[$obj] = $id;

        return $obj;

    }

    public function delete(Moxca_Blog_Taxonomy $obj)
    {
        if (!isset($this->identityMap[$obj])) {
            throw new Moxca_Blog_TaxonomyMapperException('Object has no ID, cannot delete.');
        }
        $query = $this->db->prepare('DELETE FROM moxca_blog_categories WHERE id = :id;');
        $query->bindValue(':id', $this->identityMap[$obj], PDO::PARAM_INT);
        $query->execute();
        unset($this->identityMap[$obj]);
    }

    public function getAllCategoriesAlphabeticallyOrdered()
    {
        $query = $this->db->prepare('SELECT t.id, t.term
                FROM moxca_blog_terms t
                LEFT JOIN moxca_blog_terms_taxonomy tx ON t.id = tx.term_id
                WHERE tx.taxonomy =  \'category\' ORDER BY t.term');
        $query->execute();
        $resultPDO = $query->fetchAll();
        $data = array();
        foreach ($resultPDO as $row) {
            $data[$row['id']] = $row['term'];
        }
        return $data;

    }

    public function getTermAndUri($id)
    {
        $query = $this->db->prepare('SELECT t.term, t.uri
                FROM moxca_blog_terms t
                WHERE t.id =  :id ORDER BY t.term');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $resultPDO = $query->fetchAll();
        $data = current($resultPDO);
        return $data;

    }



    private function setAttributeValue(Moxca_Blog_Taxonomy $a, $fieldValue, $attributeName)
    {
        $attribute = new ReflectionProperty($a, $attributeName);
        $attribute->setAccessible(TRUE);
        $attribute->setValue($a, $fieldValue);
    }


}