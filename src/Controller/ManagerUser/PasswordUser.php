<?php

namespace App\Controller\ManagerUser;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Service\MailerServiceController;
use App\Form\ValidateTokenType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\UserRepository;
use App\Form\PasswordResetType;
class PasswordUser extends AbstractController{

    /**
     * Vérifie si le compte existe , si oui ComfirmationResetPassword reçois un token
     * un mail est envoyé avec le token de ComfirmationResetPassword permettant à l'utilisateur
     * d'etre redirigé vers le formulaire de reset password
     * @Route("/reset-password", name="app_reset_password")
     * @param Request $request
     * @param MailerServiceController $mailerService
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function resetPassword(Request $request,MailerServiceController $mailerService)
    {
        $form = $this->createForm(ValidateTokenType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $username = $user->getEmail();
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$username]);
            if($user === null){
                $this->addFlash('danger',"Aucun compte trouvé pour cette adresse mail");
                return $this->redirectToRoute('app_reset_password');
            }else{
                $em = $this->getDoctrine()->getManager();
                //creation du token reset confirmation dans les data du user
                $user->setConfirmationResetPassword($this->generateToken());
                $em->persist($user);
                $em->flush();
                //envoi du token reset confirmation par mail
                $token = $user->getConfirmationResetPassword();
                $email = $user->getUsername();
                $firstname = $user->getFirstName();
                $mailerService->resetPassword($token,$email,$firstname,'forgotPassword.html.twig');
                $this->addFlash('success','Vous allez recevoir un mail pour réinitialiser votre mot de passe');
                return $this->redirectToRoute('app_login');
            }
        }
           return $this->render('security/reset_password.html.twig',[
               'formReset'=>$form->createView()
           ]);
    }

    /**
     * Après avoir cliqué sur le lien envoyé par mail
     * @Route("/new-password-confirm/{token}/{username}",name="app_reset_password_confirm")
     * @param $token
     * @param $username
     * @return RedirectResponse|Response
     */
    public function resetPasswordConfirm($token,$username)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email'=>$username]);
        $tokenExist = $user->getConfirmationResetPassword();
        if($token === $tokenExist){
            return $this->redirectToRoute('app_new_password',[
                "token"=>$token,
                "username"=>$username
            ]);
        }else{
            return $this->render('security/token_expire.html.twig');
        }
    }

    /**
     * Formulaire pour reset le password
     * @Route("/new-password/{token}/{username}", name="app_new_password")
     * @param Request $request
     * @param $token
     * @param $username
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function newPassword (Request $request,$token,$username,UserRepository $userRepository,UserPasswordEncoderInterface $passwordEncoder)
    {
        $email = $username;
        $user = $userRepository->findOneBy(['email'=>$email]);

        if($email == $user->getEmail() && $token == $user->getConfirmationResetPassword()){
            
            $form = $this->createForm(PasswordResetType::class);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $userRepo = $userRepository->findOneBy(['email'=>$email]);
                $user = $form->getData();
                $userRepo->setPassword($passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                ));
                $userRepo->setConfirmationResetPassword('');
                $em=$this->getDoctrine()->getManager();
                $em->persist($userRepo);
                $em->flush();
                $this->addFlash('success','Votre mot de passe a bien été modifié');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/new_password.html.twig',[
                'form'=>$form->createView()
            ]);
            
        }else{

            return $this->redirectToRoute('app_login');
        }


    }

    /**
    * Génère un token
    * @param int
    * @return string
    * @throws \Exception
    */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}