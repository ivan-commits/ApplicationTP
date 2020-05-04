<?php
namespace App\Service\Pictogramme;

use App\Repository\PictogrammeRepository;
use App\Repository\CategorieRepository;

class PictogrammeService {

    protected $pictogrammeRepository;
    protected $categorieRepository;

    public function __construct(PictogrammeRepository $pictogrammeRepository,CategorieRepository $categorieRepository)
    {
        $this->pictogrammeRepository = $pictogrammeRepository;
        $this->categorieRepository = $categorieRepository;
    }
    
    //function qui récupère tous les entity pictogrammes et les convertient en array
    public function getAllPictogramme()
    {
        $allPictogramme = $this->pictogrammeRepository->findAll();
        return $allPictogramme;
    }

    public function getAllCategorie()
    {
        $allCategorie = $this->categorieRepository->findAll();
        return $allCategorie;
        
    }

    public function getPictoByCategorie(int $id) :  array
    {
        $pictoCategorie = $this->pictogrammeRepository->findByCategorie($id);
        return $pictoCategorie;
    }
    
    public function getCategorieOfPicto(int $id) : array
    {
        $categorieOfPicto = $this->categorieRepository->findBy(['id'=>$id]);
        //convertie l'objet pictogrammes en array
        $categorieOfPicto = (array) $categorieOfPicto;
        $tab=[];
        $newTab = [];
        foreach($categorieOfPicto as $categorie){
            $categorie =  (array) $categorie;
            $tab[] = $categorie;
            foreach($tab as $valeur){
                $valeur = array_values($valeur);
                $newTab [] = $valeur;
            }
        }
        return $newTab;
    }

    public function createCarousel($pictogrammes) : array
    {
        //convertie l'objet pictogrammes en array
        $pictogrammes = (array) $pictogrammes;
        $tab = [];
        $newTab = [];
        foreach($pictogrammes as $pictogramme){
            //convertie les objets de l'array pictogrammes en array
            $pictogramme = (array) $pictogramme;
            $tab [] = $pictogramme;
        }
        //remplace les indexs par des indexs numeriques
        foreach($tab as $valeur){
            $valeur = array_values($valeur);
            $newTab [] = $valeur;
        }

        $divCarousel = array_chunk($newTab,4);
        return $divCarousel;
        

    }



}