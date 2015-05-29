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
}
