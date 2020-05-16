<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;


class LoginFromAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;
    private $userRepository;
    private $router;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(UserRepository $userRepository, RouterInterface $router,CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    //Ceci est appelé à chaque demande et votre travail consiste à décider si l'authentificateur doit être utilisé pour cette demande
    //(retour true) ou s'il doit être ignoré (retour false).
    public function supports(Request $request)
    { 
        return $request->attributes->get('_route') === 'app_login'
            && $request->isMethod('POST');   
    }

    //Lit le jeton (ou quelles que soient vos informations "d'authentification") de la demande et la renvoi. 
    //Ces informations d'identification sont transmises à getUser().
    public function getCredentials(Request $request)
    {
        $credentials =  [
            'email'=>$request->request->get('email'),
            'password'=>$request->request->get('password'),
            'csrf_token'=>$request->request->get('_csrf_token')
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );
        return $credentials;
    }

    //L'$credentials argument est la valeur renvoyée par getCredentials(). Votre travail consiste à renvoyer un objet qui implémente UserInterface.
    //Si vous le faites, alors checkCredentials()sera appelé. Si vous retournez null(ou lancez une exception AuthenticationException ) l'authentification échouera.
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token =  new CsrfToken('authenticate',$credentials['csrf_token']);
        if(!$this->csrfTokenManager->isTokenValid($token)){
            throw new InvalidCsrfTokenException();
        }
        return $this->userRepository->findOneBy(['email'=>$credentials['email']]);
    }
    //Si getUser()renvoie un objet User qui implémente UserInterface, cette méthode est appelée. 
    //Le travail consiste à vérifier si les informations d'identification sont correctes. 
    //Pour un formulaire de connexion, c'est ici qu'on vérifi que le mot de passe est correct pour l'utilisateur.
    // Pour passer l'authentification, return true. Si false (ou lancez une exception AuthenticationException ), l'authentification échouera.
    public function checkCredentials($credentials, UserInterface $user)
    {
       $credentialsValid =  $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
       if(!$credentialsValid) return false;
        $this->checkAccountValidation($user);
       return true;
    }

    public function checkAccountValidation(User $user){
        if(!$user->getEnabled()) throw new CustomUserMessageAuthenticationException('Votre compte n\'a pas été activé');
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(),$providerKey)){
            return new RedirectResponse($targetPath);
        }
        return new RedirectResponse($this->router->generate('app_home'));
    }
    
    protected function getLoginUrl()
    {
        return $this->router->generate('app_login');
    }

}
