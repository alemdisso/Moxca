<?php

class Moxca_Access_PrivilegeTester
{
    private $user;
    private $acl;
    private $allow;

    public function __construct($user, $acl, $module, $controller, $action) {
        $this->user = $user;
        $this->acl = $acl;

        if (!empty($this->user)) {
            $role = $this->user->GetRole();
        } else {
            $role = Moxca_Access_RolesConstants::ROLE_UNKNOWN;
        }

        $controllerInstance = Zend_Controller_Front::getInstance();
        $request = $controllerInstance->getRequest();

        $moduleLevel = "moxca:" . $module;
        $controllerLevel = $moduleLevel . "." . $controller;
        if ($this->acl->has($controllerLevel)) {
            $resource = $controllerLevel;
        } else {
            $resource = $moduleLevel;
        }

        if ($this->acl->has($resource) && !$this->acl->isAllowed($role, $resource, $action)) {
            $this->allow = false;

        } else {
            $this->allow = true;
        }

    }

    public function allow()
    {
        return $this->allow;
    }

}
