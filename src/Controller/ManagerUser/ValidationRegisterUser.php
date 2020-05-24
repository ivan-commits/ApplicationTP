<?php

namespace App\Controller\ManagerUser;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Service\MailerServiceController;
use App\Form\ValidateTokenType;

class ValidationRegisterUser extends AbstractController{
    
    /**
    * Envoie un mail avec un lien pour activer le compte
    * Le champ ComfirmationToken passe à null et Enable à true
    * @Route("/register/confirm/{token}/{username}", name="app_register_confirm")
    * @param $token
    * @param $username
    * @return Response
    */
    public function validationEmail($token,$username): Response
    {
        $em= $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email'=>$username]);
        $tokenExist = $user->getConfirmationToken();
        if($token === $tokenExist){
            $user->setConfirmationToken(null);
            $user->setEnabled(true);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success','Merci, votre compte est activé');
            return $this->redirectToRoute('app_login');
        }else{
            return $this->render('security/token_expire.html.twig');
        }
    }

        /**
     * Vérifie si le compte est déja valider, et si le compte existe
     * Si oui , ComfirmationToken recois un nouveau token
     * @Route("/send-token-confirmation", name="app_register_revalidation")
     * @param Request $request
     * @param $mailerService
     * @return Response
     */
    public function reValidationEmail(Request $request,MailerServiceController $mailerService)
    {
          $form = $this->createForm(ValidateTokenType::class);
          $form->handleRequest($request);

          if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $username = $user->getEmail();
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$username]);
              if($user === null){
                  $this->addFlash('danger',"Aucun compte trouvé pour cette adresse mail");
                  return $this->redirectToRoute('app_register_revalidation');
              }if( $user->getEnabled() === true){
                  $this->addFlash('danger',"Votre compte est déjà activé");
                  return $this->redirectToRoute('app_register_revalidation');
              }else{
                  $em = $this->getDoctrine()->getManager();
                  $user->setConfirmationToken($this->generateToken());
                  $em->persist($user);
                  $em->flush();
                  $token = $user->getConfirmationToken();
                  $email = $user->getUsername();
                  $firstname = $user->getFirstName();
                  $mailerService->sendToken($token,$email,$firstname,'registration.html.twig');
                  $this->addFlash('success','Vous allez recevoir un mail pour activer votre compte');
                  return $this->redirectToRoute('app_login');
              }
          }

          return $this->render('security/send_token.html.twig',[
              "formToken"=>$form->createView()
          ]);
    }
}