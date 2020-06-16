<?php

namespace App\Controller\AccountUser;

use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AccountController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class AccountUser extends AbstractController
{

    /**
     * @Route("/account", name="app_account")
     * @param Security $security
     */
    public function index(Security $security)
    {
        $user = $security->getUser();
        return $this->render('account/index.html.twig',[
            "user"=> $user
        ]);
    }


}

