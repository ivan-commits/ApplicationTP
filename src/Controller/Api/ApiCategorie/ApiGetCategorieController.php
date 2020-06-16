<?php

namespace App\Controller\Api\ApiCategorie;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository;


class ApiGetCategorieController extends AbstractController
{

    /**
     * Serialiser et normalise toutes les catégories et les envoie dans au format Json
     * @Route("/api/get/categories", name="api_get_categories", methods={"GET"})
     * @return void
     */
    public function index(CategorieRepository $categorieRepository)
    {
        //récupère toutes les catégorie trié par place et retourne une reponse Json
        return  $this->json($categorieRepository->findAllSortedByPlace(0),200,[],['groups'=>'categorie:read']);
    }

}