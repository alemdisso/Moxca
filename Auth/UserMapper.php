<?php

class Moxca_Auth_UserMapper
{

    protected $db;
    protected $identityMap;
    private $secretToken = "R2D2"; ///warning!!! changing this will block all previous passwords


    function __construct() {
        $this->db = Zend_Registry::get('db');
        $this->identityMap = new SplObjectStorage;
    }

    public function getAllIds() {
        $result = array();
        foreach ($this->db->query('SELECT id FROM moxca_auth_users;') as $row) {
            $result[] = $row['id'];
        }
        return $result;
    }

    public function insert(Moxca_Auth_User $new) {
        $data = array(
            'login' => $new->getLogin(),
            'name' => $new->GetName(),
            'password' => $this->scrambleWithToken($new->GetRawPassword()),
            'email' => $new->GetEmail(),
            'role' => $new->GetRole(),
            'status' => $new->GetStatus(),
            'first_login' => $new->GetFirstLogin(),
            'last_login' => $new->GetLastLogin(),
            );

        $this->db->insert('moxca_auth_users', $data);
        $new->SetId((int)$this->db->lastInsertId());
        $this->identityMap[$new] = $new->GetId();

    }

    public function update(Moxca_Auth_User $o) {
        if (!isset($this->identityMap[$o])) {
            throw new Moxca_Auth_UserMapperException('Object has no ID, cannot update.');
        }

        if ($o->GetRawPassword() != "") {
            $setPassword = sprintf(', password = \'%s\' ', $this->scrambleWithToken($o->GetRawPassword()));
        } else {
            $setPassword = "";
        }

        $this->db->exec(
            sprintf(
                'UPDATE moxca_auth_users SET login = \'%s\'
                    , name = \'%s\'
                    %s
                    , email = \'%s\'
                    , role = %d
                    , status = %d
                    , first_login = \'%s\'
                    , last_login = \'%s\'
                    WHERE id = %d;',
                $o->GetLogin(),
                $o->GetName(),
                $setPassword,
                $o->GetEmail(),
                $o->GetRole(),
                $o->GetStatus(),
                $o->GetFirstLogin(),
                $o->GetLastLogin(),
                $this->identityMap[$o]
            )
        );
    }

    public function findById($id) {
        $this->identityMap->rewind();
        while ($this->identityMap->valid()) {
            if ($this->identityMap->getInfo() == $id) {
                return $this->identityMap->current();
            }
            $this->identityMap->next();
        }

        $result = $this->db->fetchRow(
            sprintf(
                'SELECT login
                    , name
                    , email
                    , role
                    , status
                    , first_login
                    , last_login
                     FROM moxca_auth_users WHERE id = %d;',
                $id
            )
        );
        if (empty($result)) {
            throw new Moxca_Auth_UserMapperException(sprintf('There is no project with id #%d.', $id));
        }
        $o = new Moxca_Auth_User();

        $this->setAttributeValue($o, $id, 'id');
        $this->setAttributeValue($o, $result['login'], 'login');
        $this->setAttributeValue($o, $result['name'], 'name');
        $this->setAttributeValue($o, $result['email'], 'email');
        $this->setAttributeValue($o, $result['role'], 'role');
        $this->setAttributeValue($o, $result['status'], 'status');
        $this->setAttributeValue($o, $result['first_login'], 'firstLogin');
        $this->setAttributeValue($o, $result['last_login'], 'lastLogin');

        $this->identityMap[$o] = $id;
        return $o;
    }

    public function delete(Moxca_Auth_User $o) {
        if (!isset($this->identityMap[$o])) {
            throw new Moxca_Auth_UserMapperException('Object has no ID, cannot delete.');
        }
        $this->db->exec(
            sprintf(
                'DELETE FROM moxca_auth_users WHERE id = %d;',
                $this->identityMap[$o]
            )
        );
        unset($this->identityMap[$o]);
    }

    public function authenticateUser($login, $password)
    {
        foreach ($this->db->query(
                sprintf(
                    'SELECT id FROM moxca_auth_users WHERE login = \'%s\' AND password = \'%s\';',
                    $login,
                    $this->scrambleWithToken($password)
                    )
                )
                as $row) {
                return $this->findById($row['id']);
        }
        return null;
    }

    private function setAttributeValue(Moxca_Auth_User $o, $fieldValue, $attributeName)
    {
        $attribute = new ReflectionProperty($o, $attributeName);
        $attribute->setAccessible(TRUE);
        $attribute->setValue($o, $fieldValue);
    }

    private function scrambleWithToken($password)
    {
        return md5($password . $this->secretToken);
    }

}