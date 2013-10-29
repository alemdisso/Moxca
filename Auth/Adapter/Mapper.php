<?php
class Moxca_Auth_Adapter_Mapper implements Zend_Auth_Adapter_Interface
{
  // array containing authenticated user record
  protected $_resultArray;
  protected $db;
  private $user;

  // constructor
  // accepts username and password
  public function __construct($login, $password)
  {
    $this->db = Zend_Registry::get('db');
    $this->login = $login;
    $this->password = $password;
  }

  // main authentication method
  // queries database for match to authentication credentials
  // returns Zend_Auth_Result with success/failure code
  public function authenticate()
  {
    $userMapper = new Moxca_Auth_UserMapper($this->db);

    $user = $userMapper->authenticateUser($this->login, $this->password);

    if ($user instanceof Moxca_Auth_User && $user->GetID() > 0 ) {
      $this->user = $user;
      return new Zend_Auth_Result(
        Zend_Auth_Result::SUCCESS, $user, array());
    } else {
      $this->user = null;
      return new Zend_Auth_Result(
        Zend_Auth_Result::FAILURE, null,
          array('Authentication unsuccessful')
      );
    }
  }

  public function getAuthenticatedUser()
  {
      if ((!is_null($this->user)) && ($this->user instanceof Moxca_Auth_User)) {
          return $this->user;
      } else throw new Moxca_Auth_Adapter_MapperException('No authenticated user.');

  }

  // returns result array representing authenticated user record
  // excludes specified user record fields as needed
  public function getResultArray($excludeFields = null)
  {
    if (!$this->_resultArray) {
      return false;
    }

    if ($excludeFields != null) {
      $excludeFields = (array)$excludeFields;
      foreach ($this->_resultArray as $key => $value) {
        if (!in_array($key, $excludeFields)) {
          $returnArray[$key] = $value;
        }
      }
      return $returnArray;
    } else {
      return $this->_resultArray;
    }
  }


}
