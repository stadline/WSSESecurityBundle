<?php 

namespace Stadline\WSSESecurityBundle\Security\Authentication\Token;

use Stadline\WSSESecurityBundle\Security\User\AbstractProxyUser;
use Stadline\WSSESecurityBundle\Security\User\RepairerProxyUser;
use Stadline\WSSESecurityBundle\Security\User\SAVProxyUser;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Role\Role;

class WsseUserToken extends AbstractToken
{
    public $created;
    public $digest;
    public $nonce;
    
    /**
     *
     * @var AbstractProxyUser
     */
    public $proxyUser; 
    
    public function __construct(array $roles = array())
    {
        parent::__construct($roles);
        
        // Si l'utilisateur a des rôles, on le considère comme authentifié
        if (count($roles) > 0) {
            $this->setAuthenticated(true);
        }
    }
    
    public function getCredentials()
    {
        return $this->getRoles();
    }
    
    /**
     * Define the proxyUser given by the app
     * 
     * @param AbstractProxyUser $abstractUser
     */
    public function setProxyUserToken($token)
    {
        if(in_array(new Role('ROLE_SAV'), $this->getRoles())) {
            $this->proxyUser = new SAVProxyUser($token);
        } elseif(in_array(new Role('ROLE_REPAIRER'), $this->getRoles())) {
            $this->proxyUser = new RepairerProxyUser($token);
        } else {
            $this->proxyUser = $token;
        }
    }
    
    public function getProxyUser()
    {
        return $this->proxyUser;
    }
    
    public function getProxyUserToken()
    {
        if($this->proxyUser instanceof AbstractProxyUser) {
            return $this->proxyUser->getToken();
        } else {
            return (string) $this->proxyUser;
        }
    }

}
