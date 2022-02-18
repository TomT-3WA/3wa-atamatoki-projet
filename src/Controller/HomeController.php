<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            'contactForm' => $form->createView()
        ]);
    }
}
