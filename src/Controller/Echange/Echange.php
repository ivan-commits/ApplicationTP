<?php

namespace App\Controller\Echange;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 */
class Echange extends AbstractController
{
    /**
     * @Route("/echange", name="app_echange", methods={"GET"})
     */
    public function index()
    {
        return $this->render('echange/echange.html.twig');
    }

}
