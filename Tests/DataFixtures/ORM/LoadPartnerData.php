<?php

namespace Stadline\WSSESecurityBundle\Tests\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Stadline\WSSESecurityBundle\Entity\Partner;

class LoadPartnerData extends AbstractFixture
{

    public function load(ObjectManager $manager)
    {
        
        $partner = new Partner();
        $partner->setName('partner_name');
        $partner->setLogin('partner_login');
        $partner->setSecret('partner_secret');
        $partner->setRole('ROLE_SAV');
        
        $manager->persist($partner);
        
        $partnerRepairer = new Partner();
        $partnerRepairer->setName('partner_repairer');
        $partnerRepairer->setLogin('partner_repairer');
        $partnerRepairer->setSecret('partner_secret_repairer');
        $partnerRepairer->setRole('ROLE_REPAIRER');
        
        $manager->persist($partnerRepairer);
        
        $manager->flush();
    }
    
}
