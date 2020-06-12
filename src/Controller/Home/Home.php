<?php
namespace App\Controller\Home;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 */
class Home extends AbstractController
{

    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    //la fonction __invoke permet d'utiliser la class comme une fonction
    public function index()
    {
        return $this->render('home/index.html.twig');

    }
 
}
