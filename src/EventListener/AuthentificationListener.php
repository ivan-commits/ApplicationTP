<?php

namespace App\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\ORM\EntityManagerInterface;

class AuthentificationListener{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function onSecurityInteractivelogin(InteractiveLoginEvent $event){
        
        $user = $event->getAuthenticationToken()->getUser();
        $user->setLastLogin(new \DateTime());
        $this->em->flush();

    }

}