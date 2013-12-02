<?php

class Moxca_Taxonomy_TaxonomyMapper
{

    protected $db;
    protected $identityMap;

    function __construct()
    {
        $this->db = Zend_Registry::get('db');
        $this->identityMap = new SplObjectStorage;
    }

    public function deleteRelationship($objectId, $termId, $taxonomy)
    {

        $query = $this->db->prepare('DELETE FROM moxca_terms_relationships
                USING moxca_terms_relationships, moxca_terms_taxonomy
                WHERE moxca_terms_relationships.object = :id
                AND moxca_terms_taxonomy.id = moxca_terms_relationships.term_taxonomy
                AND moxca_terms_taxonomy.term_id = :termId
                AND moxca_terms_taxonomy.taxonomy =  :taxonomy');

        $query->bindValue(':object', $objectId, PDO::PARAM_INT);
        $query->bindValue(':termId', $termId, PDO::PARAM_INT);
        $query->bindValue(':taxonomy', $taxonomy, PDO::PARAM_STR);
        $query->execute();

    }

    public function insertCategory($termId)
    {

        $query = $this->db->prepare("INSERT INTO moxca_terms_taxonomy (term_id, taxonomy, count)
            VALUES (:termId, 'category', 0)");

        $query->bindValue(':termId', $termId, PDO::PARAM_INT);

        $query->execute();

        return (int)$this->db->lastInsertId();


    }

    public function insertRelationship($objectId, $termTaxonomyId)
    {

        $query = $this->db->prepare("INSERT INTO moxca_terms_relationships (object, term_taxonomy)
            VALUES (:object, :termTaxonomy)");

        $query->bindValue(':object', $objectId, PDO::PARAM_STR);
        $query->bindValue(':termTaxonomy', $termTaxonomyId, PDO::PARAM_STR);
        $query->execute();

        $query = $this->db->prepare("UPDATE moxca_terms_taxonomy SET count = count + 1
            WHERE id = :termTaxonomy;");
        $query->bindValue(':termTaxonomy', $termTaxonomyId, PDO::PARAM_STR);
        $query->execute();



    }

    public function insertPostCategoryRelationShip(Moxca_Blog_Post $obj)
    {
        if ($obj->getCategory() > 0) {
            $termTaxonomy = $this->createCategoryIfNeeded($obj->getCategory());
            $this->insertRelationship($obj->getId(), $termTaxonomy);
        }
    }

    public function existsCategory($termId)
    {
        $query = $this->db->prepare("SELECT id FROM moxca_terms_taxonomy WHERE term_id = :termId AND taxonomy = 'category';");

        $query->bindValue(':termId', $termId, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch();

        if (!empty($result)) {
            //$row = current($result);
            return $result['id'];
        } else {
            return false;
        }
    }

    public function updatePostCategoryRelationShip(Moxca_Blog_Post $obj)
    {

        $newCategoryTermId = $obj->getCategory();
        $postId = $obj->getId();
        $formerCategoryTermId = $this->postHasCategory($postId);

        if (!$formerCategoryTermId) {
            if ($newCategoryTermId > 0) {
                $this->insertRelationship($postId, $newCategoryTermId);
            }
        } else {
            if ($newCategoryTermId != $formerCategoryTermId) {
                $formerTermTaxonomy = $this->existsCategory($formerCategoryTermId);
                $newTermTaxonomy = $this->createCategoryIfNeeded($newCategoryTermId);

                $query = $this->db->prepare("UPDATE moxca_terms_relationships SET term_taxonomy = :newCategory"
                        . " WHERE object = :postId AND term_taxonomy = :formerCategory;");

                $query->bindValue(':postId', $postId, PDO::PARAM_STR);
                $query->bindValue(':newCategory', $newTermTaxonomy, PDO::PARAM_STR);
                $query->bindValue(':formerCategory', $formerTermTaxonomy, PDO::PARAM_STR);
                $query->execute();


                $query = $this->db->prepare("UPDATE moxca_terms_taxonomy SET count = count + 1
                    WHERE id = :termTaxonomy;");
                $query->bindValue(':termTaxonomy', $newTermTaxonomy, PDO::PARAM_STR);
                $query->execute();

                $query = $this->db->prepare("UPDATE moxca_terms_taxonomy SET count = count - 1
                    WHERE id = :termTaxonomy;");
                $query->bindValue(':termTaxonomy', $formerTermTaxonomy, PDO::PARAM_STR);

                try {
                    $query->execute();
                } catch (Exception $e) {
                    $query = $this->db->prepare("UPDATE moxca_terms_taxonomy SET count = 0
                        WHERE id = :termTaxonomy;");
                    $query->bindValue(':termTaxonomy', $formerTermTaxonomy, PDO::PARAM_STR);
                }
            }
        }

    }

    public function getAllCategoriesAlphabeticallyOrdered()
    {
        $query = $this->db->prepare('SELECT t.id, t.term
                FROM moxca_terms t
                LEFT JOIN moxca_terms_taxonomy tx ON t.id = tx.term_id
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
                FROM moxca_terms t
                WHERE t.id =  :id ORDER BY t.term');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $resultPDO = $query->fetchAll();
        $data = current($resultPDO);
        return $data;

    }

    private function setAttributeValue(Moxca_Taxonomy_Taxonomy $a, $fieldValue, $attributeName)
    {
        $attribute = new ReflectionProperty($a, $attributeName);
        $attribute->setAccessible(TRUE);
        $attribute->setValue($a, $fieldValue);
    }

    public function postHasCategory($postId)
    {
        $query = $this->db->prepare('SELECT tx.term_id
                FROM moxca_terms_relationships tr
                LEFT JOIN moxca_terms_taxonomy tx ON tr.term_taxonomy = tx.id
                WHERE tr.object = :postId
                AND tx.taxonomy =  \'category\'');

        $query->bindValue(':postId', $postId, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch();

        if (!empty($result)) {
            //$row = current($result);
            return $result['term_id'];
        } else {
            return false;
        }
    }


    private function createCategoryIfNeeded($termId)
    {
        $existsCategoryWithTerm = $this->existsCategory($termId);
        if (!$existsCategoryWithTerm) {
            $existsCategoryWithTerm = $this->insertCategory($termId);
        }

        return $existsCategoryWithTerm;

    }


}