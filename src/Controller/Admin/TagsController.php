<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Form\TagsType;
use App\Repository\TagRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tags", name="admin_tags_")
 * @package App\Controller\Admin
 */
class TagsController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TagRepository $tagsRepo): Response
    {
        return $this->render('admin/tags/index.html.twig', [
            'tags' => $tagsRepo->findAll()
        ]);
    }

    /**
     * @Route("/ajout", name="ajout")
     */
    public function ajoutTag(Request $request, ManagerRegistry $doctrine)
    {
        $tag = new Tag;

        $form = $this->createForm(TagsType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($tag);
            $manager->flush();
            $this->addFlash('success', 'Le tag a bien été ajouté');

            return $this->redirectToRoute('admin_tags_home');
        }

        return $this->render('admin/tags/ajout.html.twig', [
            'formAjoutTag' => $form->createView(),
            'id' => $tag->getId()
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="edit")
     */
    public function editTag(Tag $tag, Request $request, ManagerRegistry $doctrine)
    {
        $form = $this->createForm(TagsType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($tag);
            $manager->flush();
            $this->addFlash('success', 'Le tag a bien été modifié');

            return $this->redirectToRoute('admin_tags_home');
        }
        return $this->render('admin/tags/edit.html.twig', [
            'formModifTag' => $form->createView(),
            'id' => $tag->getId()
        ]);
    }

    /**
     * @Route("/tags/{id}/supprimer", name="delete")
     * @param Tag $tag
     * @return RedirectResponse
     */
    public function delete(Tag $tag, ManagerRegistry $doctrine): RedirectResponse
    {
        $manager = $doctrine->getManager();
        $manager->remove($tag);
        $manager->flush();

        return $this->redirectToRoute("admin_tags_home");
    }
}
