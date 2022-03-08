<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/users", name="admin_users_")
 * @package App\Controller\Admin
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function usersList(UserRepository $users)
    {
        return $this->render('admin/users/users.html.twig', [
            'users' => $users->findAll(),
        ]);
    }


    /**
     * @Route("/utilisateurs/modifier/{id}", name="edit")
     */
    public function editUser(User $user, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('message', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin_utilisateurs');
        }

        return $this->render('admin/users/edituser.html.twig', [
            'userForm' => $form->createView(),
        ]);
        return $this->render('admin/users/edituser.html.twig');
    }

    /**
     * @Route("/utilisateur/{id}/delete", name="delete")
     * @param User $user
     * @return RedirectResponse
     */
    public function delete(User $user, ManagerRegistry $doctrine): RedirectResponse
    {
        $manager = $doctrine->getManager();
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute("admin_users_list");
    }
}
