<?php


class Moxca_Access_Roles {

    private $roles = array();

    public function __construct() {
        $this->roles = array(
            Moxca_Access_RolesConstants::ROLE_UNKNOWN => _("#Unknown"), //Desconhecido
            Moxca_Access_RolesConstants::ROLE_GUEST => _("#Guest"), //Convidado
            Moxca_Access_RolesConstants::ROLE_USER => _("#User"), //Cadastrado
            Moxca_Access_RolesConstants::ROLE_ASSISTANT => _("#Assistant"), //Assistente administrativo
            Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR => _("#Administrator"), //Administrador
            Moxca_Access_RolesConstants::ROLE_SYSADMIN => _("#System administrator"), //"Administrador do Sistema"
        );

    }

    public function TitleForRole($role)
    {
            switch ($role) {
                case Moxca_Access_RolesConstants::ROLE_UNKNOWN:
                case Moxca_Access_RolesConstants::ROLE_GUEST:
                case Moxca_Access_RolesConstants::ROLE_USER:
                case Moxca_Access_RolesConstants::ROLE_ASSISTANT:
                case Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR:
                case Moxca_Access_RolesConstants::ROLE_SYSADMIN:
                    return $this->roles[$role];
                    break;

                default:
                    return "Papel desconhecido";
                    break;
            }
    }

    public function AllRoles()
    {
        return $this->roles;
    }
}
