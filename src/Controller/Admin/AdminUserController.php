<?php

namespace App\Controller\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\FullUserType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserRegistrationType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminUserController extends AbstractController
{
    private $userRepository;
    private $em;
    public function  __construct(UserRepository $userRepository,EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $users = $paginator->paginate(
            $this->userRepository->findAllQuery(),
            $request->query->getInt('page',1),
            10
        );
        return $this->render('admin/user/index.html.twig',[
            "users"=>$users
        ]);
    }
    /**
     *@Route("/admin/user/new", name="admin_user_new")
     * @return void
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $user = new User();
        $form = $this->createForm(UserRegistrationType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $user->setEnabled(true);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            ));
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success', 'Utilisateur ajouté avec succès');
            return $this->redirectToRoute('admin_user');
        }
        return $this->render('admin/user/new.html.twig', [
            "form"=>$form->createView()
        ]);
    }

    /**
     * Modifie un user
     * @Route("/admin/user/{id}", name="admin_user_edit", methods="GET|POST")
     */
    public function edit(User $user, Request $request){
        $form = $this->createForm(FullUserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin_user');
        }
        return $this->render('admin/user/edit.html.twig', [
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="admin_user_delete", methods="DELETE")
     */
    public function delete(User $user, Request $request){
        if($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token'))){
            $this->em->remove($user);
            $this->em->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        };

        return $this->redirectToRoute('admin_user');

    }

}