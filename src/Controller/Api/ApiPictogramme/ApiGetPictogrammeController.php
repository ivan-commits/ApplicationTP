<?php

namespace App\Controller\Api\ApiPictogramme;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PictogrammeRepository;


class ApiGetPictogrammeController extends AbstractController
{
    /**
     * Serialiser et normalise tous les pictogrammes et les envoie au format Json 
     * contenant tous les pictogrammes et leur catégorie associées
     * @param PictogrammeRepository $pictogrammeRepository
     * @Route("/api/get/pictogrammes", name="api_get_index", methods={"GET"})
     * @return void
     */
    public function __invoke(PictogrammeRepository $pictogrammeRepository)
    {
         return  $this->json($pictogrammeRepository->findAll(),200,[],['groups'=>'picto:read']);
    }

}