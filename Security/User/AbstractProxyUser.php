<?php 

namespace Stadline\WSSESecurityBundle\Security\User;

class AbstractProxyUser
{
    protected $token;
    
    public function __construct($token)
    {
        $this->token = $token;
    }
    
    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }
    
    public function __toString()
    {
        return $this->token;
    }
}
