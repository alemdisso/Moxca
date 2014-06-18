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
    $this->add(new Zend_Acl_Resource('moxca:admin.edition'));
    $this->add(new Zend_Acl_Resource('moxca:admin.editor'));
    $this->add(new Zend_Acl_Resource('moxca:admin.index'));
    $this->add(new Zend_Acl_Resource('moxca:admin.post'));
    $this->add(new Zend_Acl_Resource('moxca:admin.prize'));
    $this->add(new Zend_Acl_Resource('moxca:admin.serie'));
    $this->add(new Zend_Acl_Resource('moxca:admin.work'));

    $this->add(new Zend_Acl_Resource('moxca:blog'));
    $this->add(new Zend_Acl_Resource('moxca:blog.post'));


    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.panel', 'index');

    $this->allow(Moxca_Access_RolesConstants::ROLE_UNKNOWN,       'moxca:auth.login', 'index');
    $this->allow(Moxca_Access_RolesConstants::ROLE_UNKNOWN,       'moxca:auth.logout', 'index');

    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:blog.post', 'explore-not-published');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.edition', 'create');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.edition', 'change-cover');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.edition', 'change-cover-designer');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.edition', 'change-illustrator');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.edition', 'change-isbn');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.edition', 'change-pages');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.edition', 'change-serie');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.edition', 'cover');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.editor', 'create');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.index', 'list-posts');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.index', 'list-works');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.work', 'change-description');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.work', 'change-summary');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.work', 'change-title');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.work', 'change-type');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.work', 'detail');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.work', 'editions-loop');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.work', 'list');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.work', 'prizes-loop');
    $this->allow(Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR, 'moxca:admin.work', 'remove');

  }

}
