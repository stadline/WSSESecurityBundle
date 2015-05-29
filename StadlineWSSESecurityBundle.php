<?php

namespace Stadline\WSSESecurityBundle;

use Stadline\WSSESecurityBundle\DependencyInjection\Security\Factory\WsseFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class StadlineWSSESecurityBundle extends Bundle
{
     public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new WsseFactory());
    }
}
