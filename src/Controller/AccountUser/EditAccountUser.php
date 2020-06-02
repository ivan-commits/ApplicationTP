<?php
namespace App\Controller\AccountUser;

use Symfony\Component\Security\Core\Security;
use App\Form\AccountUserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
* Class EditAccountUser
* @package App\Controller
* @IsGranted("ROLE_USER")
*/

class EditAccountUser extends AbstractController{
    /**
     * Permet d'editer le profile de l'utilisateur connecté
     * @Route("/account/edit",name="app_edit_account")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request, EntityManagerInterface $em, Security $security)
    {
        $user = $security->getUser();
        $form = $this->createForm(AccountUserType::class,$user);
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
