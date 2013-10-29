<?php

class Moxca_View_Helper_Panel extends Zend_View_Helper_Abstract
{
    public function panel()
    {
        $user = Zend_Registry::get('user');
        $acl = Zend_Registry::get('acl');
        $tester = new Moxca_Access_PrivilegeTester($user, $acl, "admin", "panel", "index");
        if ($tester->allow()) {
            return $this->view->partial('panel/panel.phtml', 'admin');
        }
    }
}

