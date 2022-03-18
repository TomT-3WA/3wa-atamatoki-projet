<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Track;
use App\Form\CommentType;
use App\Form\ContactType;
use App\Form\CreateTrackType;
use App\Repository\TrackRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TrackRepository $trackRepository): Response
    {
        $tracks = $trackRepository->findAll();

        return $this->render('home/index.html.twig', [
            'tracks' => $tracks,
            'controller_name' => 'HomeController'
        ]);
    }

    /**
     * @Route("/tracks", name="tracks")
     */
    public function tracks(TrackRepository $trackRepository): Response
    {
        $tracks = $trackRepository->findAll();

        return $this->render('home/tracks.html.twig', [
            'tracks' => $tracks
        ]);
    }

    /**
     * @Route("/tracks/new", name="track_create")
     * @Route("/tracks/{id}/edit", name="track_edit")
     */
    public function form(Track $track = null, Request $request, ManagerRegistry $doctrine): Response
    {
        if (!$track) {
            $track = new Track();
        }

        $form = $this->createForm(CreateTrackType::class, $track);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$track->getId()) {
                $track->setCreatedAt(new \DateTimeImmutable());
            }
            // Upload du fichier de musique
            $uploadSong = $form->get('file')->getData();
            $uploadSongName = md5(uniqid()) . '.' . $uploadSong->guessExtension();
            $uploadSong->move($this->getParameter('upload_directory'), $uploadSongName);
            $track->setFile($uploadSongName);
            // Upload du fichier image
            // $uploadImage = $track->getImage();
            $uploadImage = $form->get('image')->getData();
            $uploadImageName = md5(uniqid()) . '.' . $uploadImage->guessExtension();
            $uploadImage->move($this->getParameter('upload_directory'), $uploadImageName);
            $track->setImage($uploadImageName);

            $manager = $doctrine->getManager();
            $manager->persist($track);
            $manager->flush();
            $this->addFlash('success', 'La track a bien été modifiée');

            return $this->redirectToRoute('tracks_show', ['id' => $track->getId()]);
        }

        return $this->render('home/create.html.twig', [
            'track' => $track,
            'createForm' => $form->createView(),
            'editMode' => $track->getId() !== null
        ]);
    }

    /**
     * @Route("/tracks/{id}/delete", name="track_delete")
     * @param Track $track
     * @return RedirectResponse
     */
    public function delete(Track $track, ManagerRegistry $doctrine): RedirectResponse
    {
        $manager = $doctrine->getManager();
        $manager->remove($track);
        $manager->flush();

        return $this->redirectToRoute("tracks");
    }

    /**
     * @Route("/tracks/{id}", name="tracks_show")
     */
    public function tracks_show(Track $track, Request $request, ManagerRegistry $doctrine)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new DateTimeImmutable())
                ->setTrack($track);

            $manager = $doctrine->getManager();
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('tracks_show', ['id' => $track->getId()]);
        }

        // dd($track->getTags());

        return $this->render('home/tracks-show.html.twig', [
            'track' => $track,
            'commentForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/ata", name="atamatoki")
     */
    public function ata(): Response
    {
        return $this->render('home/ata.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $email = (new Email())
                ->from($contact['email'])
                ->to('tom.timsit@3wa.io')
                ->subject('Hello ! Nouveau contact')
                ->text(
                    $this->renderView(
                        'emails/contact.html.twig',
                        compact('contact')
                    ),
                    'text/html'
                );

            $mailer->send($email);

            $this->addFlash('message', 'Un mail a bien été envoyé');
            return $this->redirectToRoute('home');
        }

        return $this->render('home/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
