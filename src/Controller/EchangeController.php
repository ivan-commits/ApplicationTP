<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



class EchangeController extends AbstractController
{



    /**
     * @Route("/echange", name="app_echange")
     */
    public function index()
    {
        return $this->render('echange/echange.html.twig');
    }



}
