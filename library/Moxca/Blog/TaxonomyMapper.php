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