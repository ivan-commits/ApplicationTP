<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PictogrammeType;
use App\Entity\Pictogramme;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PictogrammeRepository;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminPictoController extends AbstractController
{

    private $em;
    private $pictogrammeRepository;

    public function __construct(PictogrammeRepository $pictogrammeRepository, EntityManagerInterface $em)
    {
        $this->pictogrammeRepository = $pictogrammeRepository;
        $this->em = $em;
    }


    /**
     * @Route("/admin/picto", name="admin_picto")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $pictogrammes = $paginator->paginate(
            $this->pictogrammeRepository->findAllQuery(),
            $request->query->getInt('page',1),
            25
        );
        return $this->render('admin/picto/index.html.twig',[
            "pictogrammes"=>$pictogrammes
        ]);
    }

    /**
     *@Route("/admin/picto/new", name="admin_picto_new")
     * @return void
     */
    public function new(Request $request){
        $pictogramme = new Pictogramme();
        $form = $this->createForm(PictogrammeType::class,$pictogramme);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pictogramme = $form->getData();
            $this->em->persist($pictogramme);
            $this->em->flush();
            $this->addFlash('success', 'Pictogramme ajouté avec succès');
            return $this->redirectToRoute('admin_picto');
        }
        return $this->render('admin/picto/new.html.twig', [
            "form"=>$form->createView()
        ]);
    }

    /**
     * Modifie un pictogramme
     * @Route("/admin/picto/{id}", name="admin_picto_edit", methods="GET|POST")
     */
    public function edit(Pictogramme $pictogramme, Request $request){
        $form = $this->createForm(pictogrammeType::class,$pictogramme);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Pictogramme modifié avec succès');
            return $this->redirectToRoute('admin_picto');
        }
        return $this->render('admin/picto/edit.html.twig', [
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/admin/picto/{id}", name="admin_picto_delete", methods="DELETE")
     */
    public function delete(Pictogramme $pictogramme, Request $request){
        if($this->isCsrfTokenValid('delete' . $pictogramme->getId(), $request->get('_token'))){
            $this->em->remove($pictogramme);
            $this->em->flush();
            $this->addFlash('success', 'Pictogramme supprimé avec succès');
        };

        return $this->redirectToRoute('admin_picto');

    }

}