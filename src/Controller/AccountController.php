<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Security;
use App\Form\AccountUserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class AccountController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/account", name="app_account")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $user = $this->security->getUser();
        return $this->render('account/index.html.twig',[
            "user"=> $user
        ]);
    }

    /**
     * Permet d'editer le profile de l'utilisateur connecté
     * @Route("/account/edit",name="app_edit_account")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, EntityManagerInterface $em)
    {
        $user = $this->security->getUser();
        $form = $this->createForm(AccountUserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('app_account');
        }
        return $this->render('account/edit_account.html.twig',[
            "form"=>$form->createView()
        ]);
    }

}

