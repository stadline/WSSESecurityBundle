<?php 

namespace Stadline\WSSESecurityBundle\Security\User;

interface PartnerManagerInterface
{
    /**
     * @param string $login
     *
     * @return Partner
     */
    public function findByLogin($login);
}
