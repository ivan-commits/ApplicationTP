<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordResetType;
use App\Form\UserRegistrationType;
use App\Form\ValidateTokenType;
use App\Repository\UserRepository;
use App\Service\MailerServiceController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_index_security")
     */
    public function index()
    {
        return $this->render('security/index.html.twig');
    }

    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * Permet l'inscription d'un utilisateur
     * Le champ ComfirmationToken reçois un token et Enable passe à false
     * @Route("/register", name="app_register")
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $em
     * @param MailerServiceController $mailerService
     * @param \Swift_Mailer $mailer
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function register(AuthenticationUtils $authenticationUtils, Request $request, UserPasswordEncoderInterface $passwordEncoder,EntityManagerInterface $em, MailerServiceController $mailerService,\Swift_Mailer $mailer)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(UserRegistrationType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /** @var User $user */
            $user = $form->getData();
            $user->setEnabled(false);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            ));
            $user->setConfirmationToken($this->generateToken());
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $token = $user->getConfirmationToken();
            $email = $user->getUsername();
            $username = $user->getFirstName();

            $mailerService->sendToken($token,$email,$username,'registration.html.twig');
            $this->addFlash('success','Vous allez recevoir un mail pour activer votre compte');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig',[
            "registrationForm" => $form->createView()
        ]);
    }

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
        $em=$this->getDoctrine()->getManager();
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
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw  new \Exception('Sera intercepté avant d\'arriver ici');
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
