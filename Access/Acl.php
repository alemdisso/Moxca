<?php

class Moxca_Access_Acl extends Zend_Acl
{

public function __construct() {

    $rolesObj = new Moxca_Access_Roles();
    $roles = $rolesObj->AllRoles();
    $previousRole = null;
    while (list($role, $label) = each($roles)) {
        if ($role != Moxca_Access_RolesConstants::ROLE_SYSADMIN) {
            if ($previousRole === null) {
                $this->addRole(new Zend_Acl_Role($role));
            } else {
                $this->addRole(new Zend_Acl_Role($role), $previousRole);
            }
            $previousRole = $role;
        }


    }
    $this->addRole(new Zend_Acl_Role(Moxca_Access_RolesConstants::ROLE_SYSADMIN));
    $this->allow(Moxca_Access_RolesConstants::ROLE_SYSADMIN);

    $this->add(new Zend_Acl_Resource('moxca:auth'));
    $this->add(new Zend_Acl_Resource('moxca:auth.login'));
    $this->add(new Zend_Acl_Resource('moxca:auth.logout'));
    $this->add(new Zend_Acl_Resource('moxca:auth.user'));

    $this->add(new Zend_Acl_Resource('moxca:admin'));
    $this->add(new Zend_Acl_Resource('moxca:admin.panel'));

    $this->add(new Zend_Acl_Resource('moxca:blog'));
    $this->add(new Zend_Acl_Resource('moxca:blog.post'));

    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.panel', 'panel');

    $this->allow(Moxca_Access_RolesConstants::ROLE_UNKNOWN,       'moxca:auth.login', 'index');
    $this->allow(Moxca_Access_RolesConstants::ROLE_UNKNOWN,       'moxca:auth.logout', 'index');

    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:blog.post', 'explore-not-published');

  }

}
