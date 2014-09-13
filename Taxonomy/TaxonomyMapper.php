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
                WHERE moxca_terms_relationships.object = :object
                AND moxca_terms_taxonomy.id = moxca_terms_relationships.term_taxonomy
                AND moxca_terms_taxonomy.term_id = :termId
                AND moxca_terms_taxonomy.taxonomy =  :taxonomy');

        $query->bindValue(':object', $objectId, PDO::PARAM_INT);
        $query->bindValue(':termId', $termId, PDO::PARAM_INT);
        $query->bindValue(':taxonomy', $taxonomy, PDO::PARAM_STR);
        $query->execute();

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


    public function findTermAndInsertIfNew($term)
    {

        $term = trim($term);
        $query = $this->db->prepare('SELECT t.id
                FROM moxca_terms t
                WHERE t.term = :term');
        $query->bindValue(':term', $term, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch();
       if (empty($result)) {
            $termId = $this->insertTerm($term);
        } else {
            $termId = $result['id'];
        }
        return $termId;

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

    public function getTermByUri($uri)
    {
        $query = $this->db->prepare('SELECT t.term
                FROM moxca_terms t
                WHERE t.uri =  :uri ORDER BY t.term');
        $query->bindValue(':uri', $uri, PDO::PARAM_STR);
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


    public function decreaseTermTaxonomyCount($id, $times = 1)
    {
        if ($times > 0) {
            $query = $this->db->prepare("UPDATE moxca_terms_taxonomy SET count = count - :deleted
                WHERE id = :termTaxonomy;");
            $query->bindValue(':termTaxonomy', $id, PDO::PARAM_STR);
            $query->bindValue(':deleted', $times, PDO::PARAM_INT);
            try {
                $query->execute();
            } catch (Exception $e) {
                $query = $this->db->prepare("UPDATE moxca_terms_taxonomy SET count = 0
                    WHERE id = :termTaxonomy;");
                $query->bindValue(':termTaxonomy', $id, PDO::PARAM_STR);
                $query->execute();
            }
        }

    }

    public function insertTerm($term)
    {

        $converter = new Moxca_Util_StringToAscii;
        $uri = $converter->toAscii(trim($term));
        $query = $this->db->prepare("INSERT INTO moxca_terms (term, uri)
            VALUES (:term, :uri)");

        $query->bindValue(':term', $term, PDO::PARAM_STR);
        $query->bindValue(':uri', $uri, PDO::PARAM_STR);

        $query->execute();

        return (int)$this->db->lastInsertId();


    }


    public function purgeDeletedObject($objectId, $taxonomy)
    {
        $query = $this->db->prepare('DELETE FROM moxca_terms_relationships
                USING moxca_terms_relationships, moxca_terms_taxonomy
                WHERE moxca_terms_relationships.object = :id
                AND moxca_terms_taxonomy.id = moxca_terms_relationships.term_taxonomy
                AND moxca_terms_taxonomy.taxonomy =  :taxonomy');
        $query->bindValue(':id', $objectId, PDO::PARAM_INT);
        $query->bindValue(':taxonomy', $taxonomy, PDO::PARAM_STR);
        $query->execute();
        $rowsDeleted = $query->rowCount();

        if ($rowsDeleted > 0) {
            $this->decreaseTermTaxonomyCount($objectId, $rowsDeleted);
        }


    }

}