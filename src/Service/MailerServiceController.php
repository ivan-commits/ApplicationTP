<?php
namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;

class MailerServiceController extends AbstractController
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    private $render;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->render = $renderer;
    }

    /**
     * @param $token
     * @param $email
     * @param $template
     * @param $to
     */
    public function sendToken($token, $to, $email, $template)
    {
        $message = (new \Swift_Message('Mail de confirmation'))
            ->setFrom('noreply@terreplurielle.com')
            ->setTo($to)
            ->setBody(
                $this->renderView(
                    'emails/' . $template,
                    [
                        'token' => $token,
                        'email' => $email,
                        'username'=>$to
                    ]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }

    public function resetPassword($token, $to, $email, $template)
    {
        $message = (new \Swift_Message('Réinitialisation mot de passe'))
            ->setFrom('noreply@terreplurielle.com')
            ->setTo($to)
            ->setBody(
                $this->renderView(
                    'emails/' . $template,
                    [
                        'token' => $token,
                        'email' => $email,
                        'username'=>$to
                    ]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }

}