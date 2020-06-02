<?php

namespace App\Controller\Echange;

use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;

/**
 * @IsGranted("ROLE_USER")
 */
class Echange implements EchangeInterface
{
    /**
     * @return Response
     * @param Environment $environment
     * @Route("/echange", name="app_echange", methods={"GET"})
     */
    public function __invoke(Environment $environment)
    {
        return new Response(
            $environment->render('echange/echange.html.twig')
            );
    }

}
