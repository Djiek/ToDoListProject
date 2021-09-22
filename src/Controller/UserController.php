<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    private $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

   
    /**
     * @Route("/users", name="user_list")
     */
    public function listAction(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_ANONYMOUSLY');
        $limit = 5;
        $page = (int)$request->query->get("page", 1);
        $users = $this->repo->pagination($page, $limit);
        $total = $this->repo->getTotalUser();

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'total' => $total,
            'limit' => $limit,
            'page' => $page
        ]);
    }

    /**
     * @Route("/admin/create", name="user_create")
     */
    public function createAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

}