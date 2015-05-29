<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Stadline\WSSESecurityBundle\Command;

use Stadline\WSSESecurityBundle\Utils\WSSETools;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
 
class GenerateWsseHeaderCommand extends ContainerAwareCommand
{
    
    protected function configure()
    {
        $this->setName('wsse:wsse-generator')
                ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Username'
            )->addArgument(
                'secret',
                InputArgument::OPTIONAL,
                'secret'
            );
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $secret = $input->getArgument('secret');
        
        if($secret == null) {
            $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
            $partner = $em->getRepository('StadlineWSSESecurityBundle:Partner')
                    ->findOneBy(array('login' => $username));
            $secret = $partner->getSecret();
        }
        
        $output->writeln(WSSETools::generateWsseHeader($username, $secret));
    }
    
}
