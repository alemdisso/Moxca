<?php

class Moxca_Auth_User
{

    protected $id;
    protected $login;
    protected $name;
    protected $rawPassword;
    protected $email;
    protected $role;
    protected $status;
    protected $firstLogin = null;
    protected $lastLogin = null;


    function __construct($id=0) {
        $this->id = (int)$id;
    }

    public function GetId()
    {
        return $this->id;
    } //GetId

    public function SetId($id)
    {
        if (($this->id == 0) && ($id > 0)) {
            $this->id = (int)$id;
        } else {
            throw new Moxca_Auth_UserException('It\'s not possible to change a project\'s ID');
        }
    } //SetId

    public function GetLogin()
    {
        return $this->login;
    } //GetLogin

    public function SetLogin($login)
    {
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($login)) {
            if ($this->login != $login) {
                $this->login = $login;
            }
        } else {
            throw new Moxca_Auth_UserException("This ($login) is not a valid login.");
        }
    } //SetLogin

    public function GetName()
    {
        return $this->name;
    } //GetName

    public function SetName($name)
    {
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($name)) {
            if ($this->name != $name) {
                $this->name = $name;
            }
        } else {
            throw new Moxca_Auth_UserException("This ($name) is not a valid name.");
        }
    } //SetName

    public function GetRawPassword()
    {
        return $this->rawPassword;
    }

    public function SetPassword($password)
    {
        if ($password != "") {
            $this->rawPassword = $password;
        }
    }

    public function GetEmail()
    {
        return $this->email;
    } //GetEmail

    public function SetEmail($email)
    {
        $validator = new Moxca_Util_ValidEmail();
        if ($validator->isValid($email)) {
            if ($this->email != $email) {
                $this->email = $email;
            }
        } else {
            throw new Moxca_Auth_UserException("This ($email) is not a valid email.");
        }
    } //SetEmail

    public function GetRole()
    {
        return $this->role;
    }

    public function SetRole($role)
    {
        switch ($role) {
            case Moxca_Access_RolesConstants::ROLE_UNKNOWN:
            case Moxca_Access_RolesConstants::ROLE_GUEST:
            case Moxca_Access_RolesConstants::ROLE_USER:
            case Moxca_Access_RolesConstants::ROLE_ASSISTANT:
            case Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR:
            case Moxca_Access_RolesConstants::ROLE_SYSADMIN:
                $this->role = (int) $role;
                break;

            case null:
            case "":
            case 0:
            case false:
                $this->role = Moxca_Access_RolesConstants::ROLE_UNKNOWN;
                break;

            default:
                throw new Moxca_Access_RolesException("Invalid role.");
                break;
        }
    }


    public function SetStatus($status)
    {
        $validator = new Moxca_Util_ValidPositiveDecimal();

        if ($validator->isValid($status)) {
            $this->status = (float) $status;
        } else {
            throw new Moxca_Auth_UserException("Status must be a positive number.");
        }
    }

    public function GetStatus()
    {
        return $this->status;
    }

    public function GetFirstLogin()
    {
        return $this->firstLogin;
    } //GetFirstLogin

    public function SetFirstLogin($firstLogin)
    {
        if ($firstLogin != "") {
            $dateValidator = new Moxca_Util_ValidDate();
            if ($dateValidator->isValid($firstLogin)) {
                if ($this->firstLogin != $firstLogin) {
                    $this->firstLogin = $firstLogin;
                }
            } else {
                throw new Moxca_Auth_UserException("This ($firstLogin) is not a valid date of begin.");
            }
        }
    } //SetFirstLogin

    public function GetLastLogin()
    {
        return $this->lastLogin;
    } //GetLastLogin

    public function SetLastLogin($lastLogin)
    {
        if ($lastLogin != "") {
            $dateValidator = new Moxca_Util_ValidDate();
            if ($dateValidator->isValid($lastLogin)) {
                if ($this->lastLogin != $lastLogin) {
                    $this->lastLogin = $lastLogin;
                }
            } else {
                throw new Moxca_Auth_UserException("This ($lastLogin) is not a valid date of begin.");
            }
        }
    } //SetLastLogin


}