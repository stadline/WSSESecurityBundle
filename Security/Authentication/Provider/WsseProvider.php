<?php 

namespace Stadline\WSSESecurityBundle\Security\Authentication\Provider;

use Stadline\WSSESecurityBundle\Security\Authentication\Token\WsseUserToken;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;

class WsseProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;
    private $skipValidateDigest;
    
    public function __construct(UserProviderInterface $userProvider, $cacheDir, $skipValidateDigest = false)
    {
        $this->userProvider = $userProvider;
        $this->cacheDir = $cacheDir;
        $this->skipValidateDigest = $skipValidateDigest;
    }
    
    /**
     * @param TokenInterface $token
     * @return \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());
        
        if ($user && $this->validateDigest($token->digest, $token->nonce, $token->created, $user->getPassword())) {
            $authenticatedToken = new WsseUserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }
        
        throw new AuthenticationException('The Wsse authentication failed for username \''.$token->getUsername().'\'');
    }
    
    protected function validateDigest($digest, $nonce, $created, $secret)
    {
        //Desactivate validation of digest to override security
        if($this->skipValidateDigest == false) {
            return true;
        }
        
        // Expire le timestamp après 5 minutes
        if (time() - strtotime($created) > 300) {
            return false;
        }

        // If cache directory does not exist we create it
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }

        // Valide que le nonce est unique dans les 5 minutes
        // Le nonce peut contenir des '/' ce qui n'est pas autorisé dans un nom de fichier
        // On encrypte donc le nonce via md5 pour prévenir ce problème
        $cacheKey = md5($nonce);
        if (file_exists($this->cacheDir.'/'.$cacheKey) && file_get_contents($this->cacheDir.'/'.$cacheKey) + 300 > time()) {
            throw new NonceExpiredException('Previously used nonce detected');
        }

        file_put_contents($this->cacheDir.'/'.$cacheKey, time());
        // Valide le Secret
        $expected = base64_encode(sha1(base64_decode($nonce).$created.$secret, true));

        return $digest === $expected;
    }
    
    public function supports(TokenInterface $token)
    {
        return $token instanceof WsseUserToken;
    }
}
