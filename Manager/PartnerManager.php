<?php

namespace Stadline\WSSESecurityBundle\Manager;

use Doctrine\ORM\EntityManager;
use Exception;
use Stadline\WSSESecurityBundle\Entity\Partner;
use Stadline\WSSESecurityBundle\Entity\PartnerRepository;
use Stadline\WSSESecurityBundle\Security\User\PartnerManagerInterface;

class PartnerManager implements PartnerManagerInterface
{

    /**
     * @var EntityManager
     * 
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return PartnerRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('StadlineWSSESecurityBundle:Partner');
    }

    /**
     * Retrieve partner with the login
     * @param  string     $login
     * @return Partner
     * @throws Exception
     */
    public function findByLogin($login)
    {
        $query = $this->getRepository()->findByLogin($login)->getQuery();

        return $query->getSingleResult();
    }

    public function createNewPartner($name, $login, $role='ROLE_API')
    {
        //If it exist
        if($this->getRepository()->findOneBy(array('login' => $login))) {
            return false;
        }
        
        $partner = new Partner();
        $partner->setLogin($login);
        $partner->setName($name);
        
        $secret = md5($login.$name.time());
        
        $partner->setSecret($secret);
        $partner->setRole($role);
        
        $this->em->persist($partner);
        $this->em->flush();
        
        return $partner;
    }
    
    /**
     * Renew the secret for a Login
     * @param type $login
     * @return type
     */
    public function renewSecret($login)
    {
        $partner = $this->getRepository()->findOneBy(array('login' => $login));
        
        if($partner) {
            $secret = md5($partner->getLogin().$partner->getName().time());

            $partner->setSecret($secret);

            $this->em->persist($partner);
            $this->em->flush();
        }
        
        return $partner;
    }

}
