<?php

namespace App\Controller\AccountUser;

use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
/**
 * Class AccountController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class AccountUser implements AccountUserInterface
{

    /**
     * @Route("/account", name="app_account")
     * @return \Symfony\Component\HttpFoundation\Response
     * @param Security $security
     * @param Environment $environment
     */
    public function __invoke(Security $security, Environment $environment)
    {
        $user = $security->getUser();
        return new Response($environment->render('account/index.html.twig',[
            "user"=> $user
        ]));
    }


}

