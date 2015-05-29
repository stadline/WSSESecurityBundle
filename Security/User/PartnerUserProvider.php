<?php 

namespace Stadline\WSSESecurityBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class PartnerUserProvider implements UserProviderInterface
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;
    
    /**
     * Constructor.
     *
     * @param UserManagerInterface $userManager
     */
    public function __construct(PartnerManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function loadUserByUsername($username)
    {
        try {
            $partner = $this->userManager->findByLogin($username);

            $partnerUser = new PartnerUser($username, $partner->getSecret(), "salt", array($partner->getRole()));
            
            return $partnerUser;
            
        } catch (\Doctrine\ORM\NoResultException $e) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof PartnerUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Stadline\WSSESecurityBundle\Security\User\PartnerUser';
    }
}
