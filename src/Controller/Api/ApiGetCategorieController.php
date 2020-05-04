<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository;


class ApiGetCategorieController extends AbstractController
{

    /**
     * Serialiser et normalise toutes les catégories et les envoie dans un tableau Json
     * @Route("/api/get/categories", name="api_get_categories", methods={"GET"})
     * @return void
     */
    public function index(CategorieRepository $categorieRepository)
    {
         return  $this->json($categorieRepository->findAll(),200,[],['groups'=>'categorie:read']);

    }



}