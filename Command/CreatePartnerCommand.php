<?php

namespace Stadline\WSSESecurityBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreatePartnerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('wsse:partner:create')
                ->addArgument(
                        'name', InputArgument::REQUIRED, 'name')
                ->addArgument(
                        'login', InputArgument::REQUIRED, 'login')
                ->addArgument(
                        'role', InputArgument::REQUIRED, 'role')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $partnerManager = $this->getContainer()->get('stadline.wsse.partner_manager');
        
        $name = $input->getArgument('name');
        $login = $input->getArgument('login');
        $role = $input->getArgument('role');

        $partner = $partnerManager->createNewPartner($name, $login, $role);
        
        if($partner) {
            $output->writeln("New partner has been created : ");
            $output->writeln("name : ".$name);
            $output->writeln("login : ".$login);
            $output->writeln("secret : ".$partner->getSecret(), OutputInterface::OUTPUT_PLAIN);
        } else {
            $output->writeln("Partner already exist");
        }
        
    }

}
