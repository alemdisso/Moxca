<?php

class Moxca_Access_UserCanSeeFinances
{


    private $user;

    function __construct(Moxca_Auth_User $user)
    {
            $this->user = $user;
    }

    public function can()
    {
        $role = $this->user->GetRole();

        if (($role == Moxca_Access_RolesConstants::ROLE_SYSADMIN)
                || ($role == Moxca_Access_RolesConstants::ROLE_DIRECTOR)
                || ($role == Moxca_Access_RolesConstants::ROLE_COORDINATOR)
                || ($role == Moxca_Access_RolesConstants::ROLE_ADMINISTRATOR)
                || ($role == Moxca_Access_RolesConstants::ROLE_ASSISTANT)
                ) {
            return true;
        } else {
            return false;
        }

    }


}
