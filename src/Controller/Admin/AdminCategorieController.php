<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminCategorieController extends AbstractController
{
    private $em;
    private $categorieRepository;

    public function __construct(CategorieRepository $categorieRepository, EntityManagerInterface $em)
    {
        $this->categorieRepository = $categorieRepository;
        $this->em = $em;
    }
    /**
     * @Route("/admin/categorie", name="admin_categorie")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $categories = $paginator->paginate(
            $this->categorieRepository->findAllSortedByPlace(0),
            $request->query->getInt('page',1),
            10
        );
        return $this->render('admin/categorie/index.html.twig',[
            "categories"=>$categories
        ]);
    }

    /**
     *@Route("/admin/categorie/new", name="admin_categorie_new")
     * @return void
     */
    public function new(Request $request){
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $categorie = $form->getData();
            $this->em->persist($categorie);
            $this->em->flush();
            $this->addFlash('success', 'catégorie ajouté avec succès');
            return $this->redirectToRoute('admin_categorie');
        }
        return $this->render('admin/categorie/new.html.twig', [
            "form"=>$form->createView()
        ]);
    }

    /**
     * Modifie un categorie
     * @Route("/admin/categorie/{id}", name="admin_categorie_edit", methods="GET|POST")
     */
    public function edit(categorie $categorie, Request $request){
        
        $form = $this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'catégorie modifié avec succès');
            return $this->redirectToRoute('admin_categorie');
        }
        return $this->render('admin/categorie/edit.html.twig', [
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/admin/categorie/{id}", name="admin_categorie_delete", methods="DELETE")
     */
    public function delete(categorie $categorie, Request $request){
        if($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->get('_token'))){
            $this->em->remove($categorie);
            $this->em->flush();
            $this->addFlash('success', 'catégorie supprimé avec succès');
        };

        return $this->redirectToRoute('admin_categorie');

    }

}