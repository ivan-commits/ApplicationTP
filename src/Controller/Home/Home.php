<?php
namespace App\Controller\Home;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @IsGranted("ROLE_USER")
 */
class Home implements HomeInterface
{
    /**
     * @param Environment $environment
     * @return Response
     * @Route("/", name="app_home", methods={"GET"})
     */
    //la fonction __invoke permet d'utiliser la class comme une fonction
    public function __invoke(Environment $environment)
    {
        return new Response(
            $environment->render('home/index.html.twig')
        );
    }
 
}
