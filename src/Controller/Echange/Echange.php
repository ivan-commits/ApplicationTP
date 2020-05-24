<?php

namespace App\Controller\Echange;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



class Echange extends AbstractController
{



    /**
     * @Route("/echange", name="app_echange")
     */
    public function index()
    {
        return $this->render('echange/echange.html.twig');
    }



}
