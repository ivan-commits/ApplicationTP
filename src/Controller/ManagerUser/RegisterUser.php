<?php

namespace App\Controller\ManagerUser;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Service\MailerServiceController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\UserRegistrationType;

class RegisterUser extends AbstractController{

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
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder,
    EntityManagerInterface $em, MailerServiceController $mailerService)
    {

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